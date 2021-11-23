<?php
namespace MageReactor\BundleExtended\Model\ResourceModel\Selection;

use MageReactor\BundleExtended\Model\Product as ProductModel;
use Magento\CatalogInventory\Api\StockConfigurationInterface;

class Collection extends \Magento\Bundle\Model\ResourceModel\Selection\Collection
{

    /**
     * @var ProductModel
     */
    protected ProductModel $extendedBundleType;

    /**
     * @var StockConfigurationInterface
     */
    private StockConfigurationInterface $stockConfiguration;

    /**
     * @return ProductModel
     */
    protected function getExtendedBundleType(): ProductModel
    {
        if ($this->extendedBundleType === null) {
            $this->extendedBundleType = $this->_entityFactory->create(ProductModel::class);
        }

        return $this->extendedBundleType;
    }

    /**
     * @return $this|Collection
     */
    public function addFilterByRequiredOptions(): Collection
    {
        $this->getSelect()->joinLeft(
            ['product_options' => $this->getTable('catalog_product_option')],
            'e.entity_id = product_options.product_id AND product_options.is_require = 1',
            []
        )->where("product_options.is_require IS NULL");

        return $this;
    }

    /**
     * @return $this|Collection
     */
    public function addQuantityFilter(): Collection
    {
        return $this;
    }
}
