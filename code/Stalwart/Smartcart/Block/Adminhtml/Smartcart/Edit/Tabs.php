<?php
namespace Stalwart\Smartcart\Block\Adminhtml\Smartcart\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('smartcart_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Smartcart Information'));
    }
}