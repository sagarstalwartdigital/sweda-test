<?php

namespace Stalwart\Sweda\Setup;

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
            'jobtitle',
            [	

                'label'                 => 'Job Title',
                'input'                 => 'text',
                'required'              => false,
                'sort_order'            => 3000,
                'visible'               => true,
                'system'                => false,
                'is_used_in_grid'       => false,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => false,
                'is_searchable_in_grid' => false
            ]
        );
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'jobtitle');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'registry_id',
            [
				'type'                  => 'varchar',
                'label'                 => 'Registry Id',
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
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'registry_id');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'istore_id',
            [
				'type'                  => 'varchar',
                'label'                 => 'Istore Id',
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
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'istore_id');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'account_number',
            [
				'type'                  => 'varchar',
                'label'                 => 'Account Number',
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
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'account_number');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'company_name',
            [
                'type'                  => 'varchar',
                'label'                 => 'Company Name',
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
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'company_name');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            'customer_id',
            [
                'type'                  => 'varchar',
                'label'                 => 'Customer Id',
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
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'customer_id');
        $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
        $attribute->save();

        $eavSetup = $this->_eavSetupFactory->create(["setup"=>$setup]);
        $productTypes = [
            \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
            \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE,
            \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
            \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
            \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
        ];
        $productTypes = join(',', $productTypes);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'pro_template_file',
            [
                'group' => 'Product Details',
                'type' => 'varchar',
                'label' => 'Product Template',
                'input' => 'file',
                'backend' => 'Stalwart\Sweda\Model\Product\Attribute\Backend\File',
                'frontend' => '',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'unique' => false,
                'apply_to' => $productTypes,
                'used_in_product_listing' => false
            ]
        );
    }
}