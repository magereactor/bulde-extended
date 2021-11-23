<?php
namespace MageReactor\BundleExtended\Controller\Adminhtml\Config;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use MageReactor\BundleExtended\Api\OptionsManagementInterface;

class Options extends Action
{
    private $optionsManagement;

    public function __construct(
        Action\Context $context,
        OptionsManagementInterface $optionsManagement
    ) {
        parent::__construct($context);
        $this->optionsManagement = $optionsManagement;
    }

    public function execute()
    {
        $response = [];
        $options = [];
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $productId = $this->_request->getParam("product_id");
        $selectionId = $this->_request->getParam("selection_id");
        if($selectionId) {
            $options = $this->optionsManagement->getConfigOptionsBySelectionId((int)$selectionId);
        }
        $options = $this->optionsManagement->getOptionsByProductId((int)$productId, $options);
        if(!empty($options)) {
            $response = $options;
        }

        $resultJson->setData($response);
        return $resultJson;
    }
}