<?php

namespace Stalwart\Sweda\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Tag extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('sweda_master_tags', 'id');
	}
}