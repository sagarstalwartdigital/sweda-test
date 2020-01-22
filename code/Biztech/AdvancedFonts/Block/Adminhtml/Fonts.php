<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */
namespace Biztech\AdvancedFonts\Block\Adminhtml;

class Fonts extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'fonts';
        $this->_headerText = __('fonts');
        parent::_construct();
       
        $this->buttonList->update('add', 'label', __('Add Font'));
        $this->buttonList->update('add','onclick',"setLocation('" . $this->getUrl('advancedfonts/fonts/edit') . "')");
    }
}
