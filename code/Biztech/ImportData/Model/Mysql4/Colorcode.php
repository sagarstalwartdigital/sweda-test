<?php
namespace Biztech\ImportData\Model\Mysql4;

class Colorcode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
	protected function _construct() {
		$this->_init('productdesigner_colorcode', 'id');
	}
}
