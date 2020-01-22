<?php

namespace Biztech\PrintingMethods\Block\Adminhtml\Areasize\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    
    protected function _construct()
    {
        parent::_construct();
        $this->setId('biztech_areasize_form');
        $this->setTitle(__('Area Size'));
    }

    
    protected function _prepareForm()
    {
       
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('productdesigner/areasize/save'),
                    'method' => 'post',
                ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
