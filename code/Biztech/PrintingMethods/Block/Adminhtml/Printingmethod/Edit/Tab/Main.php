<?php
namespace Biztech\PrintingMethods\Block\Adminhtml\Printingmethod\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface {

    protected $_systemStore;
    protected $_methodType;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Magento\Framework\Registry $registry, 
        \Magento\Framework\Data\FormFactory $formFactory, 
        \Magento\Store\Model\System\Store $systemStore, 
        \Biztech\PrintingMethods\Model\Mysql4\Printingmethod\MethodType $methodType, 
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_coreRegistry = $registry;
        $this->_methodType = $methodType;
        parent::__construct($context, $registry, $formFactory, $data);
    }

   
    public function getTabLabel() {
        return __('Printing Method Information');
    }

    
    public function getTabTitle() {
        return __('Printing Method Information');
    }
    public function canShowTab() {
        return true;
    }
    public function isHidden() {
        return false;
    }
    protected function _prepareForm() {
        $model = $this->_coreRegistry->registry('current_biztech_productdesigner_printingmethod');
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        if ($id)
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Edit Printing Method')]);
        else
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Add Printing Method')]);
        if ($model->getId()) {
            $fieldset->addField('printing_id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField('printing_name', 'text', [
            'name' => 'printing_name', 
            'label' => __('Name'), 
            'title' => __('Name'), 
            'required' => true
        ]);

        $fieldset->addField('printing_description', 'textarea', [
            'name' => 'printing_description', 
            'label' => __('Description'), 
            'title' => __('Description')
        ]);

        $fieldset->addField('minimum_quantity', 'text', [
            'name' => 'minimum_quantity', 
            'label' => __('Minimum Printing Quantity'), 
            'class' => __('validate-number'), 
            'title' => __('Minimum Printing Quantity'), 
            'required' => true
        ]);

        $eventElem = $fieldset->addField('store_id', 'multiselect', [
            'name' => 'stores[]',
            'label' => __('Store Views'),
            'title' => __('Store Views'),
            'required' => true,
            'values' => $this->_systemStore->getStoreValuesForForm(false, true),
        ]);

        $method_types = $this->_methodType->toOptionArray(true);
        $eventElem = $fieldset->addField('method_type', 'select', [
            'name' => 'method_type', 
            'label' => __('Printing Method Execution'), 
            'title' => __('Printing Method Execution'), 
            'width' => __('10%'), 
            'values' => $method_types, 
            'required' => true
        ]);

        $fieldset->addField('status', 'select', [
            'name' => 'status', 
            'required' => true, 
            'label' => __('Status'), 
            'title' => __('Status'), 
            'values' => array(
                array(
                    'value' => 1,
                    'label' => __('Enabled')
                ),
                array(
                    'value' => 2,
                    'label' => __('Disabled')
                ))
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
