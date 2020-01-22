<?php
namespace Stalwart\Smartcart\Model;
class Smartcart extends \Magento\Framework\Model\AbstractModel
{
	protected $_smartCartItemCollectionFactory;
	protected $_items;
	public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Stalwart\Smartcart\Model\ResourceModel\Smartcartitem\CollectionFactory $smartCartItemCollectionFactory,
        \Stalwart\Smartcart\Model\ResourceModel\Smartcart $resource,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
    	$this->_smartCartItemCollectionFactory = $smartCartItemCollectionFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Stalwart\Smartcart\Model\ResourceModel\Smartcart');
    }
    public function getItems()
    {
    	if (null === $this->_items) {
            $this->_items = $this->_smartCartItemCollectionFactory->create();
            $this->_items->addFieldToFilter("smart_cart_id",$this->getId());
            
        }
        return $this->_items;
    }
}
?>