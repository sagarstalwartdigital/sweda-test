<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductPackagingMethodInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductPackagingMethod extends AbstractModel implements ProductPackagingMethodInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductPackagingMethod::class);
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

	public function getPackagingmethodId()
	{
		return $this->getData(self::PACKAGINGMETHOD_ID);
	}

	public function setPackagingmethodId($data)
	{
		return $this->setData(self::PACKAGINGMETHOD_ID, $data);
	}

	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	public function setProductId($data)
	{
		return $this->setData(self::PRODUCT_ID, $data);
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