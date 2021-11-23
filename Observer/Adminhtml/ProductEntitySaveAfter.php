<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Bundle\Model\Product\Type;
use Magento\Bundle\Model\SelectionFactory;
use Magento\Bundle\Model\Selection;
use Magento\Bundle\Model\ResourceModel\Selection as SelectionResourceModel;

class ProductEntitySaveAfter implements ObserverInterface
{
    const MULTI_TYPE = 'multi';

    /**
     * @var SelectionFactory
     */
    private SelectionFactory $selectionFactory;

    /**
     * @var SelectionResourceModel
     */
    private SelectionResourceModel $selectionResourceModel;

    public function __construct(
        SelectionFactory $selectionFactory,
        SelectionResourceModel $selectionResourceModel
    ){
        $this->selectionFactory = $selectionFactory;
        $this->selectionResourceModel = $selectionResourceModel;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        if ($product->getTypeId() != Type::TYPE_CODE) {
            return;
        }

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

        $bundleSelections = $product->getBundleSelectionsData();
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $selection = $objectManager->create(\Magento\Bundle\Model\Selection::class);

        if (is_array($bundleSelections)) {
            foreach ($bundleSelections as $optionSelections) {
                foreach ($optionSelections as $optionSelection) {
                    if (
                        in_array($optionSelection['option_id'], $multiOptions, true) &&
                        !empty($optionSelection['config_options-prepared-for-send'])
                    ) {
                        throw new LocalizedException(
                            __('Something went wrong')
                        );
                    }

                    if(
                        isset($optionSelection["selection_id"]) &&
                        !empty($optionSelection["selection_id"])
                    ) {
                        /**
                         * @var Selection $selectionModel
                         */
                        $selectionModel = $this->selectionFactory->create();
                        $selectionModel->load($optionSelection["selection_id"]);
                        $selectionModel->addData([
                            "config_options" => json_encode($optionSelection["config_options"])
                        ]);
                        $this->selectionResourceModel->save($selectionModel);
                    }

                }
            }
        }

    }
}
