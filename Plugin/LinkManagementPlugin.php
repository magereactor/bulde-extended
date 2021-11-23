<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Plugin;

use MageReactor\BundleExtended\Model\LinkManagement;

class LinkManagementPlugin
{
    /**
     * @param LinkManagement $subject
     * @param \Magento\Bundle\Model\Selection $selectionModel
     * @param \Magento\Bundle\Api\Data\LinkInterface $productLink
     * @param $linkedProductId
     * @param $parentProductId
     * @return null
     */
    public function beforeMapProductLinks(
        LinkManagement $subject,
        \Magento\Bundle\Model\Selection $selectionModel,
        \Magento\Bundle\Api\Data\LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ){
        if (is_array($productLink->getConfigOptions())) {
            $selectionModel->setConfigOptions(
                json_encode($productLink->getConfigOptions())
            );
        }
        return null;
    }
}
