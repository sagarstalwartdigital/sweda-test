<?php

namespace Stalwart\Sweda\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductImprintMethod extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('sweda_product_imprintmethods', 'id');
	}
}