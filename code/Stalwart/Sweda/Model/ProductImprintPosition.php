<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductImprintPositionInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductImprintPosition extends AbstractModel implements ProductImprintPositionInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductImprintPosition::class);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getPositionId()
	{
		return $this->getData(self::POSITION_ID);
	}

	public function setPositionId($data)
	{
		return $this->setData(self::POSITION_ID, $data);
	}

	public function getProductImprintmethodId()
	{
		return $this->getData(self::PRODUCT_IMPRINTMETHOD_ID);
	}

	public function setProductImprintmethodId($data)
	{
		return $this->setData(self::PRODUCT_IMPRINTMETHOD_ID, $data);
	}

}