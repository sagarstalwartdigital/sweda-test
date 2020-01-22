<?php

namespace Biztech\PrintingMethods\Block\Adminhtml\Printingmethod\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
   
    protected function _construct()
    {
        parent::_construct();
        $this->setId('biztech_printingmethod_form');
        $this->setTitle(__('Printing Method'));
    }
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('productdesigner/printingmethod/save'),
                    'method' => 'post',
                ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
