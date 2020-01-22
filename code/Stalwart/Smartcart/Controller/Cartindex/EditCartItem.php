<?php 

namespace Stalwart\Smartcart\Controller\Cartindex;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProTypeModel;
use Magento\Framework\Controller\ResultFactory;

class EditCartItem extends \Magento\Framework\App\Action\Action
{
	protected $customerSession;
	protected $logger;
	protected $_messageManager;

	/**
	* @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable
	*/
	private $_configurableProTypeModel;

	/**
	* @var PageFactory
	*/
	protected $_resultPageFactory;

	/**
	* @var JsonFactory
	*/
	protected $_resultJsonFactory;

	public function __construct(
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Stalwart\Smartcart\Model\SmartcartFactory $smartcartFactory,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		ConfigurableProTypeModel $configurableProTypeModel,
		\Magento\Framework\UrlFactory $urlFactory,
		\Magento\Framework\App\Action\Context $context

	) {
		$this->customerSession = $customerSession;
		$this->_configurableProTypeModel = $configurableProTypeModel;
		$this->_smartcartFactory = $smartcartFactory;
		$this->_resultPageFactory = $resultPageFactory;
		$this->_resultJsonFactory = $resultJsonFactory;
		$this->_messageManager = $messageManager;
		$this->urlModel = $urlFactory->create();

		parent::__construct($context);
	} 

	public function execute() {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$jsonResult = $this->_resultJsonFactory->create();
		$resultPage = $this->_resultPageFactory->create();

		if ($this->customerSession->isLoggedIn()) {
			$postedData = $this->getRequest()->getPostValue();
			if(empty($postedData))
				$postedData = $this->getRequest()->getParams();

			$saveItemData = array();

			parse_str($_POST['data'],$myt);

			parse_str($_POST['datacomments'],$usercomment);

			if (isset($myt['super_attribute']) && !empty($myt['super_attribute'])) {
								
				$simpleProdcutIdAndOptions["options"] = $myt['super_attribute'];

				foreach ($simpleProdcutIdAndOptions as $attrOpt) {
					foreach ($attrOpt as $attributeId => $attributeValue) {
						$attributeValues[$attributeId] = $attributeValue;
					}
				}
				$product = $objectManager->create('Magento\Catalog\Model\ProductRepository')->getById($postedData['configurablepro']);
				$assPro = $this->_configurableProTypeModel->getProductByAttributes($attributeValues, $product);
				$assocateProId = $assPro->getEntityId();

				if ($assocateProId && !empty($assocateProId)) {
					$simpleProdcutIdAndOptions["simple_product_id"] = $assocateProId;
				}
				$saveItemData["options"] = json_encode($simpleProdcutIdAndOptions);
			}

			if (isset($usercomment['user_comments'][$postedData['smartcartitemid']]) && !empty($usercomment['user_comments'][$postedData['smartcartitemid']])) {
				$saveItemData["usercomments"] =  $usercomment['user_comments'][$postedData['smartcartitemid']];
			} else {
				$saveItemData["usercomments"] =  NULL;
			}
			
			if (isset($postedData['smartcartitemid']) && !empty($postedData['smartcartitemid'])) {
				$smartCartItemObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcartitem')->load($postedData['smartcartitemid']);
				if (isset($postedData['smartcartitemid']) && !empty($postedData['smartcartitemid'])) {
					$saveItemData['id']	= $postedData['smartcartitemid'];
				}
				$saveItemData['smart_cart_id']	= $smartCartItemObject['smart_cart_id'];
			} else {
				$smartCartItemObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcartitem');
			}
			$smartCartItemObject->setData($saveItemData);
			$smartCartItemObject->save();

			if($this->getRequest()->getParam("dodisplaysuccessmsg",0) == 1)
				$this->messageManager->addSuccess(__("Edited Successfully Item"));

			$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($smartCartItemObject->getSmartCartId());
			$smartCartObject->setUpdatedAt(date('Y-m-d G:i:s'));
			$smartCartObject->save();
			
			$redirectReferer = $this->_redirect->getRefererUrl();
			$jsonResult->setData(['logged'  => true, "redirectUrl" => $redirectReferer]);

		}else{
			$redirectReferer = $this->_redirect->getRefererUrl();
			if($redirectReferer) {
				$redirectReferer = $this->urlModel->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
			} else {
				$redirectReferer = $this->urlModel->getUrl('customer/account/login');
			}
		}
		
	}	
}
