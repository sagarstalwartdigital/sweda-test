<?php

namespace Stalwart\Bloglist\Setup;

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

        if (version_compare($context->getVersion(), '2.0.0') < 0){

		$installer->run('create table stalwartbloglist(stalwartbloglist_id int not null auto_increment, 
bloglist_name varchar(100), 
bloglist_description varchar(100), 
bloglist_images varchar(100), 

primary key(stalwartbloglist_id))');


		}

        $installer->endSetup();

    }
}