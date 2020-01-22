<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductMaterialInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductMaterial extends AbstractModel implements ProductMaterialInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductMaterial::class);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getMaterialId()
	{
		return $this->getData(self::MATERIAL_ID);
	}

	public function setMaterialId($data)
	{
		return $this->setData(self::MATERIAL_ID, $data);
	}

	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	public function setProductId($data)
	{
		return $this->setData(self::PRODUCT_ID, $data);
	}

}