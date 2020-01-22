<?php



namespace Biztech\PrintingMethods\Block\Adminhtml\Colors\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface {

   
    public function getTabLabel() {
        return __('Color Counter Information');
    }

    
    public function getTabTitle() {
        return __('Color Counter Information');
    }

   
    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

   
    protected function _prepareForm() {

        $model = $this->_coreRegistry->registry('current_biztech_productdesigner_colors');
        $id = $this->getRequest()->getParam('id');
        
        $form = $this->_formFactory->create();
        if($id){
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Edit Color Counter')]);
        } else{
            $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Add Color Counter')]);
        }
        if ($model->getId()) {
            $fieldset->addField('colors_id', 'hidden', ['name' => 'id']);
        }
        
        $fieldset->addField('colors_counter', 'text', [
            'name' => 'colors_counter', 
            'label' => __('Color Count'), 
            'class' => __('validate-number'),
            'title' => __('Color Counter'), 
            'required' => true
        ]);

        $fieldset->addField('colors_price', 'text', [
            'name' => 'colors_price', 
            'label' => __('Cost'), 
            'title' => __('Cost'), 
            'class' => __('validate-number'),
            'required' => true
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
