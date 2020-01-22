<?php

namespace Biztech\PrintingMethods\Block\Adminhtml\Areasize\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface {

	
	public function getTabLabel() {
		return __('Area Size Information');
	}

	
	public function getTabTitle() {
		return __('Area Size Information');
	}

	
	public function canShowTab() {
		return true;
	}

	public function isHidden() {
		return false;
	}

	
	protected function _prepareForm() {

		$model = $this->_coreRegistry->registry('current_biztech_productdesigner_areasize');
		$id = $this->getRequest()->getParam('id');
		
		$form = $this->_formFactory->create();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$config = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
		$base_unit = $config->getValue('productdesigner/productdesigner_general/base_unit');


		if ($id) {
			$fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Edit Area Size')]);
		} else {
			$fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Add Area Size')]);
		}

		if ($model->getId()) {
			$fieldset->addField('areasize_id', 'hidden', ['name' => 'id']);
		}
		$fieldset->addField('area_size', 'text', [
			'name' => 'area_size', 
			'label' => __('Area Size Title'), 
			'title' => __('Area Size Title'), 
			'required' => true
		]);

		$fieldset->addField('area_size_start', 'text', [
			'name' => 'area_size_start', 
			'label' => __('Area Size Start'), 
			'title' => __('Area Size Start'), 
			'required' => true, 
			'class' => 'validate-number', 
			'note' => __("Please enter the Area Size Start. It should be in px" . $base_unit . ".")
		]);

		$fieldset->addField('area_size_end', 'text', [
			'name' => 'area_size_end', 
			'label' => __('Area Size End'), 
			'title' => __('Area Size End'), 
			'required' => true, 
			'class' => 'validate-number', 
			'note' => __("Please enter the Area Size End. It should be in px" . $base_unit . ".")
		]);

		$fieldset->addField('area_price', 'text', [
			'name' => 'area_price', 
			'label' => __('Area Size Price'), 
			'title' => __('Area Size Cost'), 
			'class' => 'validate-number', 'required' => true
		]);
		
		$form->setValues($model->getData());
		$this->setForm($form);
		return parent::_prepareForm();
	}

}
