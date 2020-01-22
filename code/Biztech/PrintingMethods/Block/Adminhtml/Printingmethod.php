<?php

namespace Biztech\PrintingMethods\Block\Adminhtml;

class Printingmethod extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'printingmethod';
        $this->_headerText = __('Printing Method');
        $this->_addButtonLabel = __('Add  Printing Method');
        parent::_construct();
    }
}
