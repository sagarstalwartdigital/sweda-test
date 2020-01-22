<?php
namespace Stalwart\Productmanager\Model\ResourceModel;

class Swedaproductshipping extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('swedaproduct_shipping', 'id');
    }
}
?>