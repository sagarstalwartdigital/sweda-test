<?php
namespace Stalwart\Bloglist\Block\Adminhtml\Bloglist\Edit;

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
        $this->setId('bloglist_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Bloglist Information'));
    }
}