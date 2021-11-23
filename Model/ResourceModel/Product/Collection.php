<?php
namespace MageReactor\BundleExtended\Model\ResourceModel\Product;

class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    /**
     * @return $this|Collection
     */
    public function addFilterByRequiredOptions(): Collection
    {
        $this->getSelect()->joinLeft(
            ['product_option' => $this->getTable('catalog_product_option')],
            'e.entity_id = product_option.product_id AND product_option.is_require = 1',
            []
        )->where("product_option.is_require IS NULL");

        return $this;
    }
}
