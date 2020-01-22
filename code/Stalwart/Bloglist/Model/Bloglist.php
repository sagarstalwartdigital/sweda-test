<?php
namespace Stalwart\Bloglist\Model;

class Bloglist extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Bloglist\Model\ResourceModel\Bloglist');
    }
}
?>