<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductImprintMethodAdvancedChargeInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductImprintMethodAdvancedCharge extends AbstractModel implements ProductImprintMethodAdvancedChargeInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductImprintMethodAdvancedCharge::class);
	}

	public function getCode()
	{
		return $this->getData(self::CODE);
	}

	public function setCode($data)
	{
		return $this->setData(self::CODE, $data);
	}

	public function getEndQty()
	{
		return $this->getData(self::END_QTY);
	}

	public function setEndQty($data)
	{
		return $this->setData(self::END_QTY, $data);
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

	public function getStartQty()
	{
		return $this->getData(self::START_QTY);
	}

	public function setStartQty($data)
	{
		return $this->setData(self::START_QTY, $data);
	}

}