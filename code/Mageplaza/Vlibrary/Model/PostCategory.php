<?php
namespace Mageplaza\Vlibrary\Model;
class PostCategory extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'mageplaza_vlibrary_post_category';

	protected $_cacheTag = 'mageplaza_vlibrary_post_category';

	protected $_eventPrefix = 'mageplaza_vlibrary_post_category';

	protected function _construct()
	{
		$this->_init('Mageplaza\Vlibrary\Model\ResourceModel\PostCategory');
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
