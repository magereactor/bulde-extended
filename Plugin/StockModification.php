<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Plugin;

use Magento\Bundle\Model\Product\Type;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Helper\Stock;
use MageReactor\BundleExtended\Model\StockManagement;

class StockModification
{
    /**
     * @var StockManagement
     */
    private StockManagement $stockManagement;


    public function __construct(
        StockManagement $stockManagement
    ){
        $this->stockManagement = $stockManagement;
    }

    public function afterAssignStatusToProduct(
        Stock $subject,
        $result,
        Product $product,
        $status = null
    ){
        if(
            $product->getTypeId() === Type::TYPE_CODE &&
            !$status
        ) {
            $isSaleable = $this->stockManagement->isSaleable($product);
            if($isSaleable) {
                $product->setIsSalable(true);
            }
        }
    }
}
