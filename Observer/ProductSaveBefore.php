<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Observer;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Exception\LocalizedException;

class ProductSaveBefore implements ObserverInterface
{

    const MULTI_TYPE = 'multi';

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $productCollectionFactory;

    /**
     * BeforeProductSaveObserver constructor.
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        CollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param EventObserver $observer
     * @throws LocalizedException
     */
    public function execute(EventObserver $observer)
    {
        /** @var Product $product */
        $product = $observer->getEvent()->getDataObject();
        $multiOptions = [];
        $bundleOptions = $product->getBundleOptionsData();
        if (is_array($bundleOptions)) {
            foreach ($bundleOptions as $bundleOption) {
                if (isset($bundleOption['type']) && $bundleOption['type'] == self::MULTI_TYPE
                    && !empty($bundleOption['option_id'])) {
                    $multiOptions[] = $bundleOption['option_id'];
                }
            }
        }

        $multiSelections = [];
        $bundleSelections = $product->getBundleSelectionsData();
        if (is_array($bundleSelections)) {
            foreach ($bundleSelections as $bundleSelection) {
                $selected = [];
                $currentOptionId = null;
                foreach ($bundleSelection as $optionSelection) {
                    if (!empty($optionSelection['option_id'])) {
                        $currentOptionId = $optionSelection['option_id'];
                    }
                    $selected[] = $optionSelection['product_id'];
                }
                if (in_array($currentOptionId, $multiOptions, true)) {
                    $multiSelections = array_merge($multiSelections, $selected);
                }
            }
        }
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->getSelect()->where('entity_id in (?)', $multiSelections)
            ->where('type_id = ?', \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE);

        if ($productCollection->count()) {
            throw new LocalizedException(__('Configurable products are not allowd to be used as multiselect'));
        }
    }
}
