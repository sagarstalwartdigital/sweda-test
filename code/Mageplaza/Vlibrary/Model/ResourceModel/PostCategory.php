<?php
namespace Mageplaza\Vlibrary\Model\ResourceModel;


class PostCategory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('mageplaza_vlibrary_post_category', 'category_id');
	}
	
}
