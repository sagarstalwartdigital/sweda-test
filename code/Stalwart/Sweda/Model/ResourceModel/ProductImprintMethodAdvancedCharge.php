<?php

namespace Stalwart\Sweda\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductImprintMethodAdvancedCharge extends AbstractDb
{
	protected function _construct()
	{
		$this->_init('sweda_product_imprintmethod_advancedcharges', 'id');
	}
}