<?php
/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\ImportData\Model;

class VirtualImages extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Biztech\ImportData\Model\Mysql4\VirtualImages');
    }
}
