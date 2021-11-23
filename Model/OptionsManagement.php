<?php
namespace MageReactor\BundleExtended\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use MageReactor\BundleExtended\Api\OptionsManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Bundle\Model\SelectionFactory;

class OptionsManagement implements OptionsManagementInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SelectionFactory
     */
    private $selectionFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        SelectionFactory $selectionFactory
    ){
        $this->productRepository = $productRepository;
        $this->selectionFactory = $selectionFactory;
    }

    /**
     * {@inheritdoc }
     */
    public function getOptionsByProductId(int $productId, array $selectedOptions): array
    {
        $options = [];
        try {
            $product = $this->productRepository->getById($productId);
            if($product->getTypeId() === Configurable::TYPE_CODE) {
                $childProducts = $product->getTypeInstance()->getUsedProducts($product);
                foreach($childProducts as $childProduct) {
                    $options[] = [
                        "selected" => in_array($childProduct->getId(), $selectedOptions),
                        "value" => $childProduct->getId(),
                        "label" => __("%sku - %name", [
                            "sku" => $childProduct->getSku(),
                            "name" => $childProduct->getName(),
                        ])
                    ];
                }
            }
        } catch (NoSuchEntityException $exception) {
            return $options;
        }
        return $options;
    }

    /**
     * {@inheritdoc }
     */
    public function getConfigOptionsBySelectionId(int $selectionId): array
    {
        $bundleSelection = $this->selectionFactory->create();
        $selection = $bundleSelection->load($selectionId);
        if($selection->getConfigOptions()) {
            return json_decode($selection->getConfigOptions());
        }
        return [];
    }

    /**
     * @param int $optionId
     * @return array|\Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    public function getSelectionsByOptionId(int $optionId)
    {
        $selections = $this->selectionFactory->create()->getCollection();
        $selections->setOptionIdsFilter(array($optionId));
        if($selections->getSize()) {
            return $selections;
        }
        return [];
    }
}