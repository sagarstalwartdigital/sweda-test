<?php

namespace Stalwart\Productmanager\Setup;

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

		$installer->run('DROP TABLE IF EXISTS `swedaproduct_master_imprintmethods`');
$installer->run('CREATE TABLE `swedaproduct_master_imprintmethods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=963');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_imprintmethods`');
$installer->run('CREATE TABLE `swedaproduct_imprintmethods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `imprint_label_id` int(11) NOT NULL,
  `matrix` varchar(255) NOT NULL,
  `full_color` int(11) NOT NULL,
  `color_included_in_price` varchar(100) NOT NULL,
  `maximum_color` varchar(100) NOT NULL,
  `location_included_in_price` varchar(100) NOT NULL,
  `maximum_location` varchar(100) NOT NULL,
  `productiont_time` int(11) NOT NULL,
  `production_unit` varchar(50) NOT NULL,
  `rule` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `imprint_label_id` (`imprint_label_id`),
  CONSTRAINT `swedaproduct_imprintmethods_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `catalog_product_entity` (`entity_id`),
  CONSTRAINT `swedaproduct_imprintmethods_ibfk_2` FOREIGN KEY (`imprint_label_id`) REFERENCES `swedaproduct_master_imprintmethods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_imprintmethods_positions`');
$installer->run('CREATE TABLE `swedaproduct_imprintmethods_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imprint_method` int(11) NOT NULL,
  `imprint_position_label` int(11) NOT NULL,
  `imprint_position_area` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `imprint_method` (`imprint_method`),
  KEY `imprint_position_label` (`imprint_position_label`),
  CONSTRAINT `swedaproduct_imprintmethods_positions_ibfk_1` FOREIGN KEY (`imprint_method`) REFERENCES `swedaproduct_imprintmethods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `swedaproduct_imprintmethods_positions_ibfk_2` FOREIGN KEY (`imprint_position_label`) REFERENCES `swedaproduct_master_imprintpositions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_imprintmethod_charges`');
$installer->run('CREATE TABLE `swedaproduct_imprintmethod_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imprint_method_id` int(11) NOT NULL,
  `charge_id` int(11) NOT NULL,
  `qty_start` int(11) DEFAULT NULL,
  `qty_end` int(11) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_master_imprintcharges`');
$installer->run('CREATE TABLE `swedaproduct_master_imprintcharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=103');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_master_imprintpositions`');
$installer->run('CREATE TABLE `swedaproduct_master_imprintpositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_price`');
$installer->run('CREATE TABLE `swedaproduct_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price_type` varchar(255) NOT NULL,
  `price_unit` varchar(255) DEFAULT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `swedaproduct_price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `catalog_product_entity` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_price_data`');
$installer->run('CREATE TABLE `swedaproduct_price_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `swedaproduct_price_id` int(11) NOT NULL,
  `qty_start` int(10) unsigned NOT NULL,
  `qty_end` int(10) unsigned NOT NULL,
  `decorative_price` decimal(12,4) NOT NULL,
  `decorative_price_code` varchar(20) NOT NULL,
  `blank_price` decimal(12,4) NOT NULL,
  `blank_price_code` varchar(20) NOT NULL,
  `special_price` decimal(12,4) NOT NULL,
  `special_price_code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `swedaproduct_price_id` (`swedaproduct_price_id`),
  CONSTRAINT `swedaproduct_price_data_ibfk_1` FOREIGN KEY (`swedaproduct_price_id`) REFERENCES `swedaproduct_price` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1');
$installer->run('DROP TABLE IF EXISTS `swedaproduct_shipping`');
$installer->run('CREATE TABLE `swedaproduct_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `shipping_qty_per_carton` varchar(100) NOT NULL,
  `carton_length` varchar(100) NOT NULL,
  `carton_width` varchar(100) NOT NULL,
  `carton_height` varchar(100) NOT NULL,
  `carton_weight` varchar(100) NOT NULL,
  `product_length` varchar(100) NOT NULL,
  `product_width` varchar(100) NOT NULL,
  `product_height` varchar(100) NOT NULL,
  `product_weight` varchar(100) NOT NULL,
  `carton_size_unit` varchar(100) NOT NULL,
  `carton_weight_unit` varchar(50) NOT NULL,
  `product_size_unit` varchar(50) NOT NULL,
  `product_weight_unit` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  CONSTRAINT `swedaproduct_shipping_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `catalog_product_entity` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1');


		//demo
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
//$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/updaterates.log');
//$logger = new \Zend\Log\Logger();
//$logger->addWriter($writer);
//$logger->info('updaterates');
//demo 

		}

        $installer->endSetup();

    }
}