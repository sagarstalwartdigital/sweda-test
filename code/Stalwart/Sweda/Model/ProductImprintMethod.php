<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductImprintMethodInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductImprintMethod extends AbstractModel implements ProductImprintMethodInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductImprintMethod::class);
	}

	public function getFullColor()
	{
		return $this->getData(self::FULL_COLOR);
	}

	public function setFullColor($data)
	{
		return $this->setData(self::FULL_COLOR, $data);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getImprintmethodId()
	{
		return $this->getData(self::IMPRINTMETHOD_ID);
	}

	public function setImprintmethodId($data)
	{
		return $this->setData(self::IMPRINTMETHOD_ID, $data);
	}

	public function getLocationPriceIncluded()
	{
		return $this->getData(self::LOCATION_PRICE_INCLUDED);
	}

	public function setLocationPriceIncluded($data)
	{
		return $this->setData(self::LOCATION_PRICE_INCLUDED, $data);
	}

	public function getMaxColorsAllowed()
	{
		return $this->getData(self::MAX_COLORS_ALLOWED);
	}

	public function setMaxColorsAllowed($data)
	{
		return $this->setData(self::MAX_COLORS_ALLOWED, $data);
	}

	public function getMaxLocationAllowed()
	{
		return $this->getData(self::MAX_LOCATION_ALLOWED);
	}

	public function setMaxLocationAllowed($data)
	{
		return $this->setData(self::MAX_LOCATION_ALLOWED, $data);
	}

	public function getPmsColorAllowed()
	{
		return $this->getData(self::PMS_COLOR_ALLOWED);
	}

	public function setPmsColorAllowed($data)
	{
		return $this->setData(self::PMS_COLOR_ALLOWED, $data);
	}

	public function getPriceIncluded()
	{
		return $this->getData(self::PRICE_INCLUDED);
	}

	public function setPriceIncluded($data)
	{
		return $this->setData(self::PRICE_INCLUDED, $data);
	}

	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	public function setProductId($data)
	{
		return $this->setData(self::PRODUCT_ID, $data);
	}

	public function getProductionDay()
	{
		return $this->getData(self::PRODUCTION_DAYS);
	}

	public function setProductionDay($data)
	{
		return $this->setData(self::PRODUCTION_DAYS, $data);
	}

	public function getProductionUnit()
	{
		return $this->getData(self::PRODUCTION_UNIT);
	}

	public function setProductionUnit($data)
	{
		return $this->setData(self::PRODUCTION_UNIT, $data);
	}

}