<?php
declare(strict_types = 1);

namespace MageReactor\BundleExtended\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;


class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();

        if (version_compare($context->getVersion(), '0.1.0', '<')) {
            $connection->addColumn(
                $setup->getTable('catalog_product_bundle_selection'),
                'config_options',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'size' => 255,
                    'comment' => 'Config Options',
                ]
            );
        }

        $setup->endSetup();
    }
}
