<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ImprintPositionInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ImprintPosition extends AbstractModel implements ImprintPositionInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ImprintPosition::class);
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