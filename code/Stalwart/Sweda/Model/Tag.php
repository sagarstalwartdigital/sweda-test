<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\TagInterface;
use Stalwart\Sweda\Model\ResourceModel;

class Tag extends AbstractModel implements TagInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\Tag::class);
	}

	public function getActive()
	{
		return $this->getData(self::ACTIVE);
	}

	public function setActive($data)
	{
		return $this->setData(self::ACTIVE, $data);
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