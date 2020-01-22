<?php
namespace Stalwart\Smartcart\Model;

class Smartcartitem extends \Magento\Framework\Model\AbstractModel
{
	protected $_productRepository;
	protected $_items;
	public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Stalwart\Smartcart\Model\ResourceModel\Smartcartitem $resource,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
    	$this->_productRepository = $productRepository;
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
        $this->_init('Stalwart\Smartcart\Model\ResourceModel\Smartcartitem');
    }
    public function getProduct()
    {
    	return $this->_productRepository->getById($this->getProductId());
    }
}
?>