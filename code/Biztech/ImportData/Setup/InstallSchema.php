<?php

namespace Biztech\ImportData\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface {

	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$installer = $setup;
		$installer->startSetup();

		$table = $installer->getConnection()->newTable(
			$installer->getTable('productdesigner_virtual_images')
		)->addColumn(
			'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Primary key of productdesigner_tabs table'
		)->addColumn(
			'virtual_product_master_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['default' => null], 'virtualProductMasterId'
		)->addColumn(
			'virtual_images', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2G', ['default' => null], 'virtualImages'
		)->setComment(
			'virtual images'
		);
		$installer->getConnection()->createTable($table);

		$table = $installer->getConnection()->newTable(
			$installer->getTable('productdesigner_designarea_printingmethod')
		)->addColumn(
			'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Primary key of productdesigner_tabs table'
		)->addColumn(
			'virtual_product_master_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['default' => null], 'virtualProductMasterId'
		)->addColumn(
			'imprint_param', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2G', ['default' => null], 'imprintParam'
		)->addColumn(
			'location', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2G', ['default' => null], 'location'
		)->addColumn(
			'imprint_method', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2G', ['default' => null], 'imprintMethod'
		)->addColumn(
			'default_imprint_method', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'default printing method id'
		)->addColumn(
			'product_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'product ID'
		)->setComment(
			'Printing method and Design area'
		);
		$installer->getConnection()->createTable($table);

		$table = $installer->getConnection()->newTable(
			$installer->getTable('productdesigner_colorcode')
		)->addColumn(
			'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Primary key of productdesigner_tabs table'
		)->addColumn(
			'color_code', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['default' => null], 'Color Code'
		)->addColumn(
			'color_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2G', ['default' => null], 'Color name'
		)->setComment(
			'color code and name'
		);
		$installer->getConnection()->createTable($table);

		$table = $installer->getConnection()->newTable(
			$installer->getTable('productdesigner_imprort_product')
		)->addColumn(
			'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Primary key of productdesigner_tabs table'
		)->addColumn(
			'productid', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['default' => null], 'Product Id'
		)->addColumn(
			'status', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2G', ['default' => null], 'status'
		)->setComment(
			'product imported'
		);
		$installer->getConnection()->createTable($table);

		$installer->endSetup();
	}
}
