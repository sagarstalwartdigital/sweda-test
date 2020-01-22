<?php
namespace Stalwart\Smartcart\Block;

use Magento\Customer\Api\CustomerRepositoryInterface;

class SmartCartDetail extends \Magento\Framework\View\Element\Template
{   
    protected $_productRepository;
    protected $_customerSession;
    protected $_smartCart;
    protected $_requestInterface;
    protected $_resultPageFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Stalwart\Smartcart\Model\Smartcart $smartCart,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Stalwart\Smartcart\Model\SmartcartFactory $smartcartfactory,
        \Magento\Framework\UrlInterface $urlInterface,
        array $data = []
    )
    {
        $this->_productRepository = $productRepository;
        $this->_customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->_smartCart = $smartCart;
        $this->imageHelper = $imageHelper;
        $this->_requestInterface = $requestInterface;
        $this->redirect = $redirect;
        $this->_resultPageFactory = $resultPageFactory;
        $this->smartcartfactory = $smartcartfactory;
        $this->urlInterface = $context->getUrlBuilder();
        parent::__construct($context, $data);
    }
    
    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    
    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }

    public function getItemImage($productId)
    {
        try {
            $_product = $this->_productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return 'product not found';
        }
        $image_url = $this->imageHelper->init($_product, 'product_base_image')->getUrl();
        return $image_url;
    }

    public function getSmartCart() {
        $id = $this->_requestInterface->getParam("id",0);

        if($id)
        {
            $smartCartObject = $this->_smartCart->load($id);
            if($smartCartObject && $smartCartObject->getId() && ($smartCartObject->getCustomerId() == $this->_customerSession->getCustomer()->getId()))
            {
                return $smartCartObject;
            }
        } else {
            return $this->_smartCart;
        }
    }

    public function getAllSmartCart() {
        $collection = $this->smartcartfactory->create()->getCollection()
                    ->addFieldToFilter('customer_id',$this->_customerSession->getCustomer()->getId());
        return $collection;
    }

    public function getSmartCartFactory() {
        $collection = $this->smartcartfactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->_customerSession->getCustomer()->getId());
        $collection->setOrder('updated_at','DESC');
        $collection->setPageSize('1');
        return $collection->getFirstItem();
    }

    public function getMiniSmartCartItemCount(){
        $collection = $this->smartcartfactory->create()->getCollection()
                        ->addFieldToFilter('customer_id',$this->_customerSession->getCustomer()->getId());
        $collection->setOrder('updated_at','DESC');
        $collection->setPageSize('1');
        $count = 0;
        if($collection)
            $count = $collection->getFirstItem()->getItems()->count();
        return $count ? $count : 0;
    }

    public function getConfigurableOptions($product) {
        $resultPage = $this->_resultPageFactory->create();
        $configurableOptionHtml = $resultPage->getLayout()
                        ->createBlock('Magento\ConfigurableProduct\Block\Product\View\Type\Configurable')
                        ->setProduct($product)
                        ->setTemplate('Magento_ConfigurableProduct::product/view/type/options/configurable.phtml')
                        ->toHtml();
        return $configurableOptionHtml;
    }

    /**
     * return Congigurable associated product id.
     * @param object $productId
     * @param array  $nameValueList
     * @return bool|int
     */

    public function getProductRegularPrices($pid)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productListObj = $objectManager->create('Stalwart\Sweda\Block\ProductPricesRegular');
        return $productListObj->setBlockProductId($pid)->getProductRegularPrices();
        
    }
    public function getProductImprintMethod($pid)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productListObj = $objectManager->create('Stalwart\Sweda\Block\ProductPricesRegular');
        return $productListObj->setBlockProductId($pid)->getProductImprintMethod();
    }

    public function getProductRegularPriceTypes($pid)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productListObj = $objectManager->create('Stalwart\Sweda\Block\ProductPricesRegular');
        return $productListObj->setBlockProductId($pid)->getProductRegularPriceTypes();
    }

    public function getIsLoggedIn(){
        return $this->_customerSession->isLoggedIn();
    }

    public function redirectIfNotLoggedIn()
    {
        if (!$this->_customerSession->isLoggedIn()) {
            $this->_customerSession->setAfterAuthUrl($this->urlInterface->getCurrentUrl());
            $this->_customerSession->authenticate();
        }
    }

    public function getIsBtobPending(){
        return $this->customerRepository->getById($this->_customerSession->getCustomerId())->getCustomAttribute('is_btob')->getValue();
    }

}
?>
