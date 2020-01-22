<?php
namespace Mageplaza\Vlibrary\Model;
class PostTopic extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'mageplaza_vlibrary_post_topic';

	protected $_cacheTag = 'mageplaza_vlibrary_post_topic';

	protected $_eventPrefix = 'mageplaza_vlibrary_post_topic';

	protected function _construct()
	{
		$this->_init('Mageplaza\Vlibrary\Model\ResourceModel\PostTopic');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}
