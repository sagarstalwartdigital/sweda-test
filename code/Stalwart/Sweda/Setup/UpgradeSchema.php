<?php

namespace Stalwart\Sweda\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '3.0.0', '<')) {

        	$installer = $setup;
			$installer->startSetup();

			$table = $installer->getConnection()
				->newTable($installer->getTable('sweda_invoice'))
	            ->addColumn(
	                'invoice_number',
	                Table::TYPE_INTEGER,
	                null,
	                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
	                'Invoice Number (TRX_NUMBER)'
	            )
	            ->addColumn(
	                'customer_id',
	                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
	                null,
	                ['nullable' => false],
	                'Customer id',
	                ['identity' => false, 'unsigned' => true, 'nullable' => false], 'customer_id'
	            )
	            ->addColumn(
	                'order_number',
	                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
	                null,
	                ['nullable' => false],
	                'order number'
	            )
	            ->addColumn(
	                'total_amount',
	                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
	                null,
	                ['nullable' => false],
	                'Total Amount'
	            )
	            ->addColumn(
	                'waybill_number',
	                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
	                null,
	                ['nullable' => false],
	                'WAYBILL_NUMBER'
	            )
	            ->addColumn(
	                'trx_date',
	                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
	                null,
	                ['nullable' => false],
	                'TRX_DATE'
	            )
	            ->addColumn(
	                'ship_actual_date',
	                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
	                null,
	                ['nullable' => true],
	                'SHIP_DATE_ACTUAL'
	            )
	            ->addColumn(
	                'due_date',
	                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
	                null,
	                ['nullable' => true],
	                'DUE_DATE'
	            )
	            ->addColumn(
	                'purchase_order',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                50,
	                ['nullable' => true],
	                'PURCHASE_ORDER'
	            )
	            ->addColumn(
	                'ship_via',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                50,
	                ['nullable' => true],
	                'SHIP_VIA'
	            )
	            ->addColumn(
	                'terms',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                50,
	                ['nullable' => true],
	                'TERMS'
	            )
	            ->addColumn(
	                'billing_customer_name',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                500,
	                ['nullable' => false],
	                'Billing customer name'
	            )->addColumn(
	                'billing_address1',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                200,
	                ['nullable' => false],
	                'Billing address1'
	            )
	            ->addColumn(
	                'billing_address2',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                200,
	                ['nullable' => true],
	                'Billing address2'
	            )
	            ->addColumn(
	                'billing_city_state',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                200,
	                ['nullable' => false],
	                'Billing city and state'
	            )
	            ->addColumn(
	                'billing_country',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                100,
	                ['nullable' => false],
	                'Billing country'
	            )
	            ->addColumn(
	                'created_at',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
	                null,
	                ['nullable' => false],
	                'Created At'
	            )
	            ->addColumn(
	                'updated_at',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
	                null,
	                [],
	                'Updated At'
	            )
	            ->addIndex(
	                $setup->getIdxName(
	                    'sweda_invoice',
	                    ['invoice_number'],
	                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
	                ),
	                ['invoice_number'],
	                ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
	            )
	            ->setComment('Oracle invoice');

			$installer->getConnection()->createTable($table);
			$installer->endSetup();

        }
        if (version_compare($context->getVersion(), '4.0.0', '<')) {
        	$installer = $setup;
			$installer->startSetup();
			$installer->getConnection()->modifyColumn($installer->getTable('sweda_invoice'),'waybill_number',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'length' => 255,
					'nullable' => true,
					'comment' => 'Waybill Number'
				]);
			$installer->endSetup();
        }
    }
}
