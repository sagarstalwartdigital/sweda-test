<?php
namespace Biztech\PrintingMethods\Block\Adminhtml\Printingmethod\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('biztech_productdesigner_printingmethod_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Printing Method Information'));


        $id = $this->getRequest()->getParam('id');
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $type = $objectManager->create('Biztech\PrintingMethods\Model\Printingmethod')->load($id)->getMethodType();

        if($type == 1):            
            $this->addTabAfter(
                'bycolorquantity',
                [
                    'label' => __('By Color Quantity'),
                    'url' => $this->getUrl('*/*/bycolorquantity', ['_current' => true]),
                    'class' => 'ajax',
                    
                ],'main_section'
            );
            
        endif;
        
        if($type == 2):
            $this->addTabAfter(
                'byareasize',
                [
                    'label' => __('By Area Size'),
                    'url' => $this->getUrl('*/*/byareasize', ['_current' => true]),
                    'class' => 'ajax',
                    
                ],'main_section'
            );
        endif;
    }
}
