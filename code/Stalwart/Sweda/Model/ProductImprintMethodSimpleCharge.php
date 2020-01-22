<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductImprintMethodSimpleChargeInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductImprintMethodSimpleCharge extends AbstractModel implements ProductImprintMethodSimpleChargeInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductImprintMethodSimpleCharge::class);
	}

	public function getCode()
	{
		return $this->getData(self::CODE);
	}

	public function setCode($data)
	{
		return $this->setData(self::CODE, $data);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getImprintmethodChargeId()
	{
		return $this->getData(self::IMPRINTMETHOD_CHARGE_ID);
	}

	public function setImprintmethodChargeId($data)
	{
		return $this->setData(self::IMPRINTMETHOD_CHARGE_ID, $data);
	}

	public function getProductImprintmethodId()
	{
		return $this->getData(self::PRODUCT_IMPRINTMETHOD_ID);
	}

	public function setProductImprintmethodId($data)
	{
		return $this->setData(self::PRODUCT_IMPRINTMETHOD_ID, $data);
	}

	public function getRate()
	{
		return $this->getData(self::RATE);
	}

	public function setRate($data)
	{
		return $this->setData(self::RATE, $data);
	}

}