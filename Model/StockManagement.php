<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use MageReactor\BundleExtended\Api\StockManagementInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\Store\Model\StoreManagerInterface;

class StockManagement implements StockManagementInterface
{
    /**
     * @var StockResolverInterface
     */
    private StockResolverInterface $stockResolver;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        StockResolverInterface $stockResolver,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository
    ){
        $this->stockResolver = $stockResolver;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc }
     */
    public function isSaleable(
        ProductInterface $product
    ): bool {
        $options = $product->getTypeInstance()->getOptionsCollection($product);
        $stockModel = $this->stockResolver->execute("website", $this->storeManager->getWebsite()->getCode());
//        echo "<pre>";
//        print_r(get_class_methods($product->getTypeInstance()));
//        exit;
        $selections = $product->getTypeInstance()->getSelectionsCollection(array_keys($options->getItems()), $product);
        $isSaleable = false;
        foreach ($selections->getItems() as $selection) {
            if (
                (int)$selection->getStatus() === Status::STATUS_ENABLED
                && $selection->getTypeId() == Configurable::TYPE_CODE
            ) {
                $itemProduct = $this->productRepository->get($selection->getSku(), false, $this->storeManager->getStore()->getId());
                if($itemProduct->getIsSalable()) {
                    $isSaleable = true;
                    break;
                }
            }
        }
        return $isSaleable;
    }
}
