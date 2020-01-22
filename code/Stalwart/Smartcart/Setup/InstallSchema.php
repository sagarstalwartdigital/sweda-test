<?php

namespace Stalwart\Smartcart\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0){

		$installer->run('DROP TABLE IF EXISTS `invi_smartcart_item`');
$installer->run('DROP TABLE IF EXISTS `invi_smart_cart`');
$installer->run('CREATE TABLE `invi_smart_cart` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`customer_id` INT(10) UNSIGNED NOT NULL,
	`title` varchar(255),
	`recepient_data` text,
	`event_name` varchar(255),
	`event_date` date,
	`description` text,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX invi_smartcart_customer_id (`customer_id` ASC),
	CONSTRAINT invi_sc_fk_customer_id FOREIGN KEY (`customer_id`)
		REFERENCES customer_entity(`entity_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('CREATE TABLE `invi_smartcart_item` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`smart_cart_id` int(10) UNSIGNED NOT NULL,
	`product_id` int(10) NOT NULL,
	`options` text,
	`usercomments` text,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	CONSTRAINT invi_sci_fk_id FOREIGN KEY (`smart_cart_id`)
		REFERENCES invi_smart_cart(`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1');

		}

        $installer->endSetup();

    }
}