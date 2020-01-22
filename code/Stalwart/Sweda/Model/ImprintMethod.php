<?php

namespace Stalwart\Sweda\Model;

use Magento\Framework\Model\AbstractModel;
use Stalwart\Sweda\Api\Data\ImprintMethodInterface;
use Stalwart\Sweda\Model\ResourceModel;

class ImprintMethod extends AbstractModel implements ImprintMethodInterface
{
	protected function _construct()
	{
		$this->_init(ResourceModel\ImprintMethod::class);
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