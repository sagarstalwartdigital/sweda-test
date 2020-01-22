<?php
namespace Biztech\ImportData\Model\ResourceModel\MediaGallery;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {
	public function _construct() {
		$this->_init('Biztech\ImportData\Model\MediaGallery', 'Biztech\ImportData\Model\ResourceModel\MediaGallery');
	}
}
