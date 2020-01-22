<?php
 
namespace Stalwart\Sweda\Block;
 
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
 
class EstimateShipping extends Template
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    protected $_registry;

    /**
     * ModalOverlay constructor.
     *
     * @param BlockRepositoryInterface $blockRepository
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Stalwart\Productmanager\Model\SwedaproductshippingFactory $swedaProductShippingFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->blockRepository = $blockRepository;
        $this->_registry = $registry;
        $this->_productRepository = $productRepository;
        $this->swedaProductShippingFactory = $swedaProductShippingFactory;
        $this->customerSession = $customerSession;

        parent::__construct($context, $data);
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    public function getProductShippingData()
    {
        $qty = $this->getQty();
        $product_id =  $this->getProId();
        $collection = $this->swedaProductShippingFactory->create()->getCollection()->addFieldToFilter('pid',$product_id)->getFirstItem();
        return $collection;
    }

    public function getTotalWeightForShipping() 
    {
        $qty = $this->getQty();
        $product_id =  $this->getProId();
        $collection = $this->swedaProductShippingFactory->create()->getCollection()->addFieldToFilter('pid',$product_id)->getFirstItem();
        if (!empty($collection['shipping_qty_per_carton']) && !empty($collection['carton_weight'])) {
            $totalShipWeight = $qty * ($collection['carton_weight'] / $collection['shipping_qty_per_carton']);
            return $totalShipWeight;
        } else {
            return '';
        }
    }

    public function getTotalPackageForShipping() 
    {
        $qty = $this->getQty();
        $product_id =  $this->getProId();
        $collection = $this->swedaProductShippingFactory->create()->getCollection()->addFieldToFilter('pid',$product_id)->getFirstItem();
        if (!empty($collection['shipping_qty_per_carton'])) {
            $totalShipPackages = $qty / $collection['shipping_qty_per_carton'];
            return $totalShipPackages;
        } else {
            return '';
        }
    }
}
