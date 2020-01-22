<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductPriceCallInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductPriceCall extends AbstractModel implements ProductPriceCallInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductPriceCall::class);
	}

}