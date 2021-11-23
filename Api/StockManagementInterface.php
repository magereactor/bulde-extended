<?php
namespace MageReactor\BundleExtended\Api;

use Magento\Catalog\Api\Data\ProductInterface;

interface StockManagementInterface
{
    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function isSaleable(
        ProductInterface $product
    ): bool;
}
