<?php

namespace Stalwart\Sweda\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ImprintMethod extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('sweda_master_imprintmethods', 'id');
	}
}