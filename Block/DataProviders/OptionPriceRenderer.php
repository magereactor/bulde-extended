<?php
namespace MageReactor\BundleExtended\Block\DataProviders;

use Magento\Catalog\Model\Product;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Bundle\Block\DataProviders\OptionPriceRenderer as ParentOptionPriceRenderer;


class OptionPriceRenderer extends ParentOptionPriceRenderer
{
    /**
     * Format tier price string
     *
     * @param Product $selection
     * @param array $arguments
     * @return string
     */
    public function renderTierPrice(Product $selection, array $arguments = []): string
    {
        if ($selection->getTypeId() == Configurable::TYPE_CODE) {
            return '';
        }
        return parent::renderTierPrice($selection, $arguments);
    }
}
