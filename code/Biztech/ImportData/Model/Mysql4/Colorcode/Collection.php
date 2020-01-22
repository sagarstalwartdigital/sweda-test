<?php

namespace Biztech\ImportData\Model\Mysql4\Colorcode;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
	protected function _construct() {
		$this->_init('Biztech\ImportData\Model\Colorcode', 'Biztech\ImportData\Model\Mysql4\Colorcode');
	}
}
