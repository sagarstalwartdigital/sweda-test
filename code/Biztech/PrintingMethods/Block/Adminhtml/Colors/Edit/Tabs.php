<?php

namespace Biztech\PrintingMethods\Block\Adminhtml\Colors\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
  
    protected function _construct()
    {
        parent::_construct();
        $this->setId('biztech_productdesigner_colors_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Color Counter Information'));
    }
}
