<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\MaterialInterface;
use Stalwart\Sweda\Model\ResourceModel;

class Material extends AbstractModel implements MaterialInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\Material::class);
	}

	public function getId()
	{
		return $this->getData(self::ID);
	}

	public function setId($data)
	{
		return $this->setData(self::ID, $data);
	}

	public function getName()
	{
		return $this->getData(self::NAME);
	}

	public function setName($data)
	{
		return $this->setData(self::NAME, $data);
	}

}