<?php
namespace MageReactor\BundleExtended\Model;

use Magento\Bundle\Api\Data\LinkInterface;
use Magento\Bundle\Model\Selection;

class LinkManagement extends \Magento\Bundle\Model\LinkManagement
{
    /**
     * @param Selection $selectionModel
     * @param LinkInterface $productLink
     * @param string $linkedProductId
     * @param string $parentProductId
     * @return Selection
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function mapProductLinkToSelectionModel(
        Selection $selectionModel,
        LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ) {
        $selectionModel = parent::mapProductLinkToSelectionModel(
            $selectionModel,
            $productLink,
            $linkedProductId,
            $parentProductId
        );

        $selectionModel = $this->mapProductLinks(
            $selectionModel,
            $productLink,
            $linkedProductId,
            $parentProductId
        );

        return $selectionModel;
    }

    /**
     * @param Selection $selectionModel
     * @param LinkInterface $productLink
     * @param $linkedProductId
     * @param $parentProductId
     */
    public function mapProductLinks(
        Selection $selectionModel,
        LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ){
        return $selectionModel;
    }
}
