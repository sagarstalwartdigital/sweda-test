<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\BrandInterface;
use Stalwart\Sweda\Model\ResourceModel;

class Brand extends AbstractModel implements BrandInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\Brand::class);
	}

}