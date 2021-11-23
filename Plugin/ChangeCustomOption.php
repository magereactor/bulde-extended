<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Plugin;

use Magento\Catalog\Model\Product;

class ChangeCustomOption
{
    /**
     * @param Product $subject
     * @param $code
     * @param $value
     * @param null $product
     * @return array
     */
    public function beforeAddCustomOption(
        Product $subject,
        $code,
        $value,
        $product = null
    ): array {
        if($code === "bundle_identity") {
            $valueExploded = explode('_', $value);
            if (empty($valueExploded) || (count($valueExploded) - 1) % 2 !== 0) {
                return [$code, $value, $product];
            }

            /** @var \Magento\Catalog\Model\Product\Configuration\Item\Option $buyRequest */
            if (!($buyRequest = $subject->getCustomOption('info_buyRequest'))) {
                return [$code, $value, $product];
            }

            $buyRequestValue = json_decode($buyRequest->getValue(), true);
            $superAttributes = $buyRequestValue['bundle_super_attribute'] ?? [];

            for ($i = 1; $i < count($valueExploded) - 1; $i += 2) {
                $selectionId = $valueExploded[$i];
                $simpleIds = $superAttributes[$selectionId] ?? [];
                if (!empty($simpleIds)) {
                    $value .= '_' . implode('_', $simpleIds);
                }
            }
        }
        return [
            $code,
            $value,
            $product
        ];
    }
}
