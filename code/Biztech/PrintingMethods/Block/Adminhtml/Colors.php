<?php

namespace Biztech\PrintingMethods\Block\Adminhtml;

class Colors extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'colors';
        $this->_headerText = __('Image Colors');
        $this->_addButtonLabel = __('Add  Color Counter');
        parent::_construct();
    }
}
