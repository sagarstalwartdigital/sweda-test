<?php

namespace Biztech\PrintingMethods\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
		$installer = $setup;
		$installer->startSetup();

		/**
		 * Creating table biztech_printingmethods
		 */
		if (!$setup->getConnection()->isTableExists($setup->getTable('productdesigner_printing_method'))) {
			$table = $setup->getConnection()->newTable(
				$setup->getTable('productdesigner_printing_method')
			)->addColumn(
				'printing_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Primary Key of productdesigner_printing_method table'
			)->addColumn(
				'printing_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 0], 'Printing name'
			)->addColumn(
				'printing_description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => '0'], 'Printing description'
			)->addColumn(
				'store_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => '0'], 'Store id'
			)->addColumn(
				'status', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false, 'default' => '0'], 'Status'
			)->addColumn(
				'minimum_quantity', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false, 'default' => '0'], 'Minium Quanitity'
			)->addColumn(
				'method_type', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false, 'default' => '0'], 'Printing method type'
			)->addColumn(
				'printingtype_ids', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => ''], 'Printing type IDs'
			)
			->setComment(
				'Printing Method for Products'
			);
			$setup->getConnection()->createTable($table);
		}

		//Color counter table
		if (!$setup->getConnection()->isTableExists($setup->getTable('productdesigner_colors'))) {
			$table = $setup->getConnection()->newTable(
				$setup->getTable('productdesigner_colors')
			)->addColumn(
				'colors_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'primary Key of productdesigner_colors table'
			)->addColumn(
				'colors_counter', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['nullable' => false, 'default' => 0], 'Colors counter'
			)->addColumn(
				'colors_price', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => ''], 'Price'
			)->setComment(
				'Color Counter for Configurable Products'
			);
			$setup->getConnection()->createTable($table);
		}

		//Create Areasize Table
		if (!$setup->getConnection()->isTableExists($setup->getTable('productdesigner_areasize'))) {
			$table = $setup->getConnection()->newTable(
				$setup->getTable('productdesigner_areasize')
			)->addColumn(
				'areasize_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'primary Key of productdesigner_areasize table'
			)->addColumn(
				'area_price', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => ''], 'Price'
			)->addColumn(
				'area_size', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 0], 'Area size'
			)->addColumn(
				'area_size_start', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 0], 'Starting area size'
			)->addColumn(
				'area_size_end', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => 0], 'Ending area size'
			)->setComment(
				'Area size'
			);
			$setup->getConnection()->createTable($table);
		}

		$installer->endSetup();
	}
}