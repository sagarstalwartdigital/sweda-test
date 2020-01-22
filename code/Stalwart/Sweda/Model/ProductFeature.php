<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductFeatureInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductFeature extends AbstractModel implements ProductFeatureInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductFeature::class);
	}

}