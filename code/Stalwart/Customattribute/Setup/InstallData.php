<?php

namespace Stalwart\Customattribute\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Catalog\Model\ResourceModel\Product as ResourceProduct;

/**
 * Class InstallData
 *
 * @package VVV\Comercial\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var Config
     */
    private $eavConfig;

     /**
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * @var AttributeSet
     */
    protected $_attributeSet;

    protected $_resourceProduct;

    /**
     * @param Config $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSet $AttributeSet
     */

    /**
     * Init
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    
    public function __construct(
        \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory,
        Config $eavConfig,
        EavSetupFactory $eavSetupFactory,
        ResourceProduct $resourceProduct,
        AttributeSet $attributeSet
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavConfig            = $eavConfig;
        $this->_eavSetupFactory     = $eavSetupFactory;
        $this->_attributeSet    = $attributeSet;
        $this->_resourceProduct = $resourceProduct;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'asi_number',
            [
                'type'                  => 'varchar',
                'label'                 => 'ASI Number',
                'input'                 => 'text',
                'required'              => false,
                'sort_order'            => 3000,
                'visible'               => true,
                'system'                => false,
                'is_used_in_grid'       => false,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false
            ]);
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'asi_number');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();


        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'tax_id_number',
            [
                'type'                  => 'varchar',
                'label'                 => 'Tax ID Number',
                'input'                 => 'text',
                'required'              => false,
                'sort_order'            => 3000,
                'visible'               => true,
                'system'                => false,
                'is_used_in_grid'       => false,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false
            ]);
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'tax_id_number');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();


        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'information_email_address',
            [
                'type'=> 'text',
                'label' => 'Email Address',
                'validate_rules' => '{"input_validation":"email"}',
                'input' => 'text',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'system'=> false,
                'group'=> 'General',
                'global' => true,
                'visible_on_front' => true,
            ]);
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'information_email_address');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();


        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute('customer_address', 'ap_contact', [
            'type' => 'varchar',
            'label' => 'A/P Contact',
            'input' => 'text',
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'system'=> false,
            'group'=> 'General',
            'global' => true,
            'visible_on_front' => true,
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'ap_contact');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']
        );
        $attribute->save();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute('customer_address', 'ap_email_address', [
            'type' => 'text',
            'label' => 'A/P Email Address',
            'input' => 'text',
            'validate_rules' => '{"input_validation":"email"}',
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'system'=> false,
            'group'=> 'General',
            'global' => true,
            'visible_on_front' => true,
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'ap_email_address');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']
        );
        $attribute->save();


        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'federal_classification',
            [
                'type' => 'varchar',
                'label' => 'Federal Tax Classification',
                'input' => 'text',
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'system'=> false,
                'group'=> 'General',
                'global' => true,
                'visible_on_front' => true,
            ]);
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'federal_classification');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();
    }
}