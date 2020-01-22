<?php

namespace Biztech\PrintingMethods\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Directory\Helper\Data;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {

    private $eavSetupFactory;
    protected $_resource;
    private $directoryData;

    public function __construct(
    EavSetupFactory $eavSetupFactory, Data $directoryData, \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_resource = $resource;
        $this->directoryData = $directoryData;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {

        $installer = $setup;
        $installer->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY, 'printingmethodattr', [
                'type' => 'varchar',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => '',
                'label' => 'Printing Method',
                'input' => 'multiselect',
                'visible' => true,
                'required' => false,
                'apply_to' => 'configurable,simple',
                'source' => 'Biztech\PrintingMethods\Model\Entity\Attribute\Source\Printingmethodattr',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'group' => 'Product Designer'
            ]
        );
    }
}
