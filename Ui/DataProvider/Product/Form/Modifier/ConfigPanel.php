<?php
namespace MageReactor\BundleExtended\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form;
use Magento\Ui\Component\Form\Element\MultiSelect;

class ConfigPanel extends AbstractModifier
{
    const CODE_BUNDLE_DATA = 'bundle-items';
    const CODE_BUNDLE_OPTIONS = 'bundle_options';

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @var ArrayManager
     */
    protected ArrayManager $arrayManager;

    /**
     * @var LocatorInterface
     */
    protected LocatorInterface $locator;

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        ArrayManager $arrayManager
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->arrayManager = $arrayManager;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function modifyMeta(array $meta): array
    {
        $path = $this->arrayManager->findPath(
            static::CODE_BUNDLE_DATA,
            $meta,
            null,
            'children'
        );

        return $this->arrayManager->merge(
            $path,
            $meta,
            [
                'children' => [
                    self::CODE_BUNDLE_OPTIONS => $this->getOptions()
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data): array
    {
        return $data;
    }

    /**
     * Get Bundle Options structure
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'children' => [
                'record' => [
                    'children' => [
                        'product_bundle_container' => [
                            'children' => [
                                'bundle_selections' => [
                                    'children' => [
                                        'record' => [
                                            'children' => [
                                                'config_options' => [
                                                    'arguments' => [
                                                        'data' => [
                                                            'config' => [
                                                                'component' => 'MageReactor_BundleExtended/js/components/config-options',
                                                                'formElement' => MultiSelect::NAME,
                                                                'componentType' => Form\Field::NAME,
                                                                'label' => __('Configurable Options'),
                                                                'dataScope' => 'config_options',
                                                                'sortOrder' => 280,
                                                                'visible' => true,
                                                                'url' => $this->urlBuilder->getUrl(
                                                                    'bundleextended/config/options'
                                                                ),
                                                                'imports' => [
                                                                    'product_id' => '${ $.provider }:${ $.parentScope }.product_id',
                                                                    'selection_id' => '${ $.provider }:${ $.parentScope }.selection_id',
                                                                    '__disableTmpl' => ['product_id' => false, 'selection_id' => false],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
