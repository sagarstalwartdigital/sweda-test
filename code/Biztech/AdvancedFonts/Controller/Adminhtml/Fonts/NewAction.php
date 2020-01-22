<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\AdvancedFonts\Controller\Adminhtml\Fonts;
header("Access-Control-Allow-Origin: *");
class NewAction extends \Biztech\Productdesigner\Controller\Adminhtml\Fonts
{

    public function execute()
    {	
    	
        $this->_forward('edit');
    }
}
