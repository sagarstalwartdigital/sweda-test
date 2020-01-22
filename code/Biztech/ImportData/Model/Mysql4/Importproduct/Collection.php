<?php

namespace Biztech\ImportData\Model\Mysql4\Importproduct;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
	protected function _construct() {
		$this->_init('Biztech\ImportData\Model\Importproduct', 'Biztech\ImportData\Model\Mysql4\Importproduct');
	}
}
