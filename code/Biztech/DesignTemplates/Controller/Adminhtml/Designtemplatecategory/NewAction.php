<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\DesignTemplates\Controller\Adminhtml\Designtemplatecategory;
header("Access-Control-Allow-Origin: *");
class NewAction extends \Biztech\DesignTemplates\Controller\Adminhtml\Designtemplatecategory
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
