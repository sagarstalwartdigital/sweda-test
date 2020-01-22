<?php

namespace Biztech\PrintingMethods\Block\Adminhtml;

class Areasize extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'areasize';
        $this->_headerText = __('Area size');
        $this->_addButtonLabel = __('Add  Area Size');
        parent::_construct();
    }
}
