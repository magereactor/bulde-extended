<?php
namespace MageReactor\BundleExtended\Model;

class Product extends \Magento\Catalog\Model\Product
{
    const NON_COMPOSITE_PRODUCT_TYPES = 'non_composite_product_types';

    /**
     * @return array
     */
    public function getNonCompositeProductTypes(): array
    {
        return (array)$this->getData(self::NON_COMPOSITE_PRODUCT_TYPES);
    }

    /**
     * @return bool
     */
    public function isComposite(): bool
    {
        if (in_array($this->getTypeId(), $this->getNonCompositeProductTypes())) {
            return false;
        }
        return parent::isComposite();
    }
}
