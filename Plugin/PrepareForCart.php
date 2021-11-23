<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Plugin;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\DataObject;

class PrepareForCart
{
    /**
     * @param Configurable $subject
     * @param DataObject $buyRequest
     * @param $product
     * @param null $processMode
     * @return array
     */
    public function beforePrepareForCartAdvanced(
        Configurable $subject,
        DataObject $buyRequest,
        $product,
        $processMode = null
    ): array {
        $selectionId = $product->getSelectionId();
        $superAttributes = (array)$buyRequest->getBundleSuperAttribute();
        if ($selectionId && isset($superAttributes[$selectionId])) {
            $buyRequest->setSuperAttribute($superAttributes[$selectionId]);
        }
        return [$buyRequest, $product, $processMode];
    }
}
