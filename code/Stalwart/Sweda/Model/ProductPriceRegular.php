<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductPriceRegularInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductPriceRegular extends AbstractModel implements ProductPriceRegularInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductPriceRegular::class);
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

	public function getMaxQty()
	{
		return $this->getData(self::MAX_QTY);
	}

	public function setMaxQty($data)
	{
		return $this->setData(self::MAX_QTY, $data);
	}

	public function getMinQty()
	{
		return $this->getData(self::MIN_QTY);
	}

	public function setMinQty($data)
	{
		return $this->setData(self::MIN_QTY, $data);
	}

	public function getPriceType()
	{
		return $this->getData(self::PRICE_TYPE);
	}

	public function setPriceType($data)
	{
		return $this->setData(self::PRICE_TYPE, $data);
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