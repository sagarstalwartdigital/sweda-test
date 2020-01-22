<?php
namespace Stalwart\Sweda\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    private $customerSetupFactory;
    private $eavSetupFactory;
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }
    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $setup->startSetup();
        
        if (version_compare($context->getVersion(), "9.0.0", "<")) {
            $setup->startSetup();

            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            // Add new customer attribute
            $customerSetup->addAttribute(
                Customer::ENTITY,
                'is_btob',
                [
                    'type' => 'int',
                    'label' => 'Is B2B',
                    'input' => 'select',
                    'source' => 'Stalwart\Sweda\Model\Config\Source\CustomerBtobStatus',
                    'required'              => false,
                    'sort_order'            => 3000,
                    'visible'               => true,
                    'system'                => false,
                    'is_used_in_grid'       => false,
                    'is_visible_in_grid'    => false,
                    'is_filterable_in_grid' => false,
                    'default' => 1,
                    'is_searchable_in_grid' => false
                ]);
            // add attribute to form
            /** @var  $attribute */
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'is_btob');
            $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
            $attribute->save();

            $customerSetup->addAttribute(
                Customer::ENTITY,
                'start_date',
                [
                    'type' => 'static',
                    'label' => 'Start date',
                    'input' => 'date',
                    'source' => '',
                    'frontend' => \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class,
                    'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\Datetime::class,
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
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'start_date');
            $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
            $attribute->save();

            $customerSetup->addAttribute(
                Customer::ENTITY,
                'end_date',
                [
                    'type' => 'static',
                    'label' => 'End date',
                    'input' => 'date',
                    'source' => '',
                    'frontend' => \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class,
                    'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\Datetime::class,
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
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'end_date');
            $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']);
            $attribute->save();

            $setup->endSetup();
            return $setup;
        }

        if (version_compare($context->getVersion(), "20.0.0", "<")) {
            $setup->startSetup();

            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            // Add new customer attribute
            $customerSetup->addAttribute('customer_address', 'contact_email', [
                'type' => 'text',
                'label' => 'Email',
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

            $attribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'contact_email');
            $attribute->setData('used_in_forms', ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','checkout_register','customer_account_create','customer_account_edit','customer_address_edit','customer_register_address']
            );
            $attribute->save();

            $setup->endSetup();
            return $setup;
        }

        if (version_compare($context->getVersion(), "11.0.0", "<")) {
            $setup->startSetup();

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
                'user_id',
                [
                    'type'                  => 'varchar',
                    'label'                 => 'User Id',
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
            $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'user_id');
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


            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

            $customerSetup->removeAttribute(
                'customer_address', 'mobile'
            );
            $customerSetup->removeAttribute(
                'customer_address', 'website'
            );
            $customerSetup->removeAttribute(
                'customer_address', 'fax'
            );

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


            $setup->endSetup();
            return $setup;
        }

        if (version_compare($context->getVersion(), "18.0.0", "<")) {

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            
            $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'promo_banner_content', [
                'type' => 'text',
                'label' => 'Promo Banner Content',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 30,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                ]
            );

            $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'promo_banner_main_heading', [
                'type' => 'varchar',
                'label' => 'Promo Banner Main Heading',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                ]
            );

            $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'promo_banner_tag_line', [
                'type' => 'varchar',
                'label' => 'Promo Banner Tag Line',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 20,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                ]
            );

            $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'promo_banner_main_image', [
                'type' => 'varchar',
                'label' => 'Promo Banner Image',
                'input' => 'image',
                'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                'required' => false,
                'sort_order' => 40,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                ]
            );

            $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'promo_banner_link', [
                'type' => 'varchar',
                'label' => 'Link For Shop Now',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 20,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                ]
            );

        }

        if (version_compare($context->getVersion(), "21.0.0", "<")) {

            $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY, 'catagory_name_mapping', [
                'type' => 'varchar',
                'label' => 'Category Name Mapping',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 30,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
                ]
            );
        }
        
        $setup->endSetup();
    }
}