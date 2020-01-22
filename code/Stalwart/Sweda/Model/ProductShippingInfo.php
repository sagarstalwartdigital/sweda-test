<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductShippingInfoInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductShippingInfo extends AbstractModel implements ProductShippingInfoInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductShippingInfo::class);
	}

	public function getCartonCapacity()
	{
		return $this->getData(self::CARTON_CAPACITY);
	}

	public function setCartonCapacity($data)
	{
		return $this->setData(self::CARTON_CAPACITY, $data);
	}

	public function getCartonHeight()
	{
		return $this->getData(self::CARTON_HEIGHT);
	}

	public function setCartonHeight($data)
	{
		return $this->setData(self::CARTON_HEIGHT, $data);
	}

	public function getCartonLength()
	{
		return $this->getData(self::CARTON_LENGTH);
	}

	public function setCartonLength($data)
	{
		return $this->setData(self::CARTON_LENGTH, $data);
	}

	public function getCartonSizeUnit()
	{
		return $this->getData(self::CARTON_SIZE_UNIT);
	}

	public function setCartonSizeUnit($data)
	{
		return $this->setData(self::CARTON_SIZE_UNIT, $data);
	}

	public function getCartonWeight()
	{
		return $this->getData(self::CARTON_WEIGHT);
	}

	public function setCartonWeight($data)
	{
		return $this->setData(self::CARTON_WEIGHT, $data);
	}

	public function getCartonWeightUnit()
	{
		return $this->getData(self::CARTON_WEIGHT_UNIT);
	}

	public function setCartonWeightUnit($data)
	{
		return $this->setData(self::CARTON_WEIGHT_UNIT, $data);
	}

	public function getCartonWidth()
	{
		return $this->getData(self::CARTON_WIDTH);
	}

	public function setCartonWidth($data)
	{
		return $this->setData(self::CARTON_WIDTH, $data);
	}

	public function getFobCountry()
	{
		return $this->getData(self::FOB_COUNTRY);
	}

	public function setFobCountry($data)
	{
		return $this->setData(self::FOB_COUNTRY, $data);
	}

	public function getFobState()
	{
		return $this->getData(self::FOB_STATE);
	}

	public function setFobState($data)
	{
		return $this->setData(self::FOB_STATE, $data);
	}

	public function getFobZipcode()
	{
		return $this->getData(self::FOB_ZIPCODE);
	}

	public function setFobZipcode($data)
	{
		return $this->setData(self::FOB_ZIPCODE, $data);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getProductHeight()
	{
		return $this->getData(self::PRODUCT_HEIGHT);
	}

	public function setProductHeight($data)
	{
		return $this->setData(self::PRODUCT_HEIGHT, $data);
	}

	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	public function setProductId($data)
	{
		return $this->setData(self::PRODUCT_ID, $data);
	}

	public function getProductLength()
	{
		return $this->getData(self::PRODUCT_LENGTH);
	}

	public function setProductLength($data)
	{
		return $this->setData(self::PRODUCT_LENGTH, $data);
	}

	public function getProductSizeUnit()
	{
		return $this->getData(self::PRODUCT_SIZE_UNIT);
	}

	public function setProductSizeUnit($data)
	{
		return $this->setData(self::PRODUCT_SIZE_UNIT, $data);
	}

	public function getProductWeight()
	{
		return $this->getData(self::PRODUCT_WEIGHT);
	}

	public function setProductWeight($data)
	{
		return $this->setData(self::PRODUCT_WEIGHT, $data);
	}

	public function getProductWeightUnit()
	{
		return $this->getData(self::PRODUCT_WEIGHT_UNIT);
	}

	public function setProductWeightUnit($data)
	{
		return $this->setData(self::PRODUCT_WEIGHT_UNIT, $data);
	}

	public function getProductWidth()
	{
		return $this->getData(self::PRODUCT_WIDTH);
	}

	public function setProductWidth($data)
	{
		return $this->setData(self::PRODUCT_WIDTH, $data);
	}

}