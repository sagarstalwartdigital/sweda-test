<?php

namespace Biztech\PrintingMethods\Block\Adminhtml\Areasize\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{

    protected function _construct()
    {
        parent::_construct();
        $this->setId('biztech_productdesigner_areasize_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Area size Information'));
    }
}
