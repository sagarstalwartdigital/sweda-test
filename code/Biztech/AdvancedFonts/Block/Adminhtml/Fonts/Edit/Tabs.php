<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */
namespace Biztech\AdvancedFonts\Block\Adminhtml\Fonts\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('biztech_productdesigner_fonts_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Fonts Information'));
    }
}
