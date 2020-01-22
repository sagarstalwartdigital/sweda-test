<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductExtraInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductExtra extends AbstractModel implements ProductExtraInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductExtra::class);
	}

	public function getCountry()
	{
		return $this->getData(self::COUNTRY);
	}

	public function setCountry($data)
	{
		return $this->setData(self::COUNTRY, $data);
	}

	public function getCurrency()
	{
		return $this->getData(self::CURRENCY);
	}

	public function setCurrency($data)
	{
		return $this->setData(self::CURRENCY, $data);
	}

	public function getDistributorCentralUrl()
	{
		return $this->getData(self::DISTRIBUTOR_CENTRAL_URL);
	}

	public function setDistributorCentralUrl($data)
	{
		return $this->setData(self::DISTRIBUTOR_CENTRAL_URL, $data);
	}

	public function getLanguage()
	{
		return $this->getData(self::LANGUAGE);
	}

	public function setLanguage($data)
	{
		return $this->setData(self::LANGUAGE, $data);
	}

	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	public function setProductId($data)
	{
		return $this->setData(self::PRODUCT_ID, $data);
	}

	public function getProductionTemplate()
	{
		return $this->getData(self::PRODUCTION_TEMPLATE);
	}

	public function setProductionTemplate($data)
	{
		return $this->setData(self::PRODUCTION_TEMPLATE, $data);
	}

	public function getProp65Statu()
	{
		return $this->getData(self::PROP65_STATUS);
	}

	public function setProp65Statu($data)
	{
		return $this->setData(self::PROP65_STATUS, $data);
	}

	public function getSpecialPriceTill()
	{
		return $this->getData(self::SPECIAL_PRICE_TILL);
	}

	public function setSpecialPriceTill($data)
	{
		return $this->setData(self::SPECIAL_PRICE_TILL, $data);
	}

	public function getValidUpto()
	{
		return $this->getData(self::VALID_UPTO);
	}

	public function setValidUpto($data)
	{
		return $this->setData(self::VALID_UPTO, $data);
	}

	public function getVat()
	{
		return $this->getData(self::VAT);
	}

	public function setVat($data)
	{
		return $this->setData(self::VAT, $data);
	}

	public function getVatUnit()
	{
		return $this->getData(self::VAT_UNIT);
	}

	public function setVatUnit($data)
	{
		return $this->setData(self::VAT_UNIT, $data);
	}

	public function getVideoUrl()
	{
		return $this->getData(self::VIDEO_URL);
	}

	public function setVideoUrl($data)
	{
		return $this->setData(self::VIDEO_URL, $data);
	}

}