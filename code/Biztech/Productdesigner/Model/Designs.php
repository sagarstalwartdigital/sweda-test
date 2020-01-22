<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\Productdesigner\Model;

class Designs extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Biztech\Productdesigner\Model\Mysql4\Designs');
    }
}