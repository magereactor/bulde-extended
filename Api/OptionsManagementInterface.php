<?php
namespace MageReactor\BundleExtended\Api;

interface OptionsManagementInterface
{
    /**
     * @param int $productId
     * @param array $selectedOptions
     * @return array
     */
    public function getOptionsByProductId(int $productId, array $selectedOptions): array;

    /**
     * @param int $selectionId
     * @return array
     */
    public function getConfigOptionsBySelectionId(int $selectionId): array;
}