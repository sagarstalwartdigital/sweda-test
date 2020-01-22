<?php
namespace Biztech\ImportData\Model;

use Magento\Framework\Model\AbstractModel;

class MediaGallery extends AbstractModel {
	public function _construct() {
		$this->_init('Biztech\ImportData\Model\ResourceModel\MediaGallery');
	}
}