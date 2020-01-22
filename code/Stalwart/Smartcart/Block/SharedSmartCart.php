<?php
namespace Stalwart\Smartcart\Block;
class SharedSmartCart extends \Magento\Framework\View\Element\Template
{   
    protected $_productRepository;
    protected $_customerSession;
    protected $_smartCart;
    protected $_requestInterface;
    protected $_resultPageFactory;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Stalwart\Smartcart\Model\Smartcart $smartCart,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Stalwart\Smartcart\Model\SmartcartFactory $smartcartfactory,
        array $data = []
    )
    {
        $this->_productRepository = $productRepository;
        $this->_customerSession = $customerSession;
        $this->_smartCart = $smartCart;
        $this->imageHelper = $imageHelper;
        $this->_requestInterface = $requestInterface;
        $this->redirect = $redirect;
        $this->_resultPageFactory = $resultPageFactory;
        $this->smartcartfactory = $smartcartfactory;
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

        $toShare = $this->getRequest()->getParam('share','');
        $toShareCartData = base64_decode(urldecode($toShare));
        $paramStrToArray = explode("&",$toShareCartData);

        $paramArray = array();
        foreach ($paramStrToArray as $key => $value) {
            list($paramLabel, $paramValue) = explode("=",$value);
            $paramArray[$paramLabel] = $paramValue;
        }

        $id = $paramArray['id'];

        if($id)
        {
            $smartCartObject = $this->_smartCart->load($id);
            if($smartCartObject && $smartCartObject->getId())
            {
                return $smartCartObject;
            }
        } else {
            return $this->_smartCart;
        }
    }

    public function getSenderName() {
        $toShare = $this->getRequest()->getParam('share','');
        $toShareCartData = base64_decode(urldecode($toShare));
        $paramStrToArray = explode("&",$toShareCartData);

        $paramArray = array();
        foreach ($paramStrToArray as $key => $value) {
            list($paramLabel, $paramValue) = explode("=",$value);
            $paramArray[$paramLabel] = $paramValue;
        }

        return $paramArray['name'];
    }

}
?>
