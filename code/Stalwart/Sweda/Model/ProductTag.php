<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ProductTagInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ProductTag extends AbstractModel implements ProductTagInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ProductTag::class);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getProductId()
	{
		return $this->getData(self::PRODUCT_ID);
	}

	public function setProductId($data)
	{
		return $this->setData(self::PRODUCT_ID, $data);
	}

	public function getTagId()
	{
		return $this->getData(self::TAG_ID);
	}

	public function setTagId($data)
	{
		return $this->setData(self::TAG_ID, $data);
	}

}