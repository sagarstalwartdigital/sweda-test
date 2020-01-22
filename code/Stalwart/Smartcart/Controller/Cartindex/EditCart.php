<?php 

namespace Stalwart\Smartcart\Controller\Cartindex;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableProTypeModel;
use Magento\Framework\Controller\ResultFactory;

class EditCart extends \Magento\Framework\App\Action\Action
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

			$error = 0;
			$saveData = array();
			if(isset($postedData['editcartid']) && !empty($postedData['editcartid'])) {
				$saveData['id']	= $postedData['editcartid'];		
			}
			$saveData["customer_id"] = $this->customerSession->getCustomerId();
			$saveData["title"] = (isset($postedData["title"]) && !empty($postedData["title"])) ? $postedData["title"] : $error = 1;

			$recipientData = array();
			if((isset($postedData["recipientname"]) && !empty($postedData["recipientname"])) && (isset($postedData["recipientemail"]) && !empty($postedData["recipientemail"])))
			{
				if(count($postedData["recipientname"]) == count($postedData["recipientemail"]))
				{
					foreach($postedData["recipientemail"] as $key => $value)
					{
						if(!empty($value) && (isset($postedData["recipientname"][$key]) && !empty($postedData["recipientname"][$key])))
							$recipientData[$value]['name'] = $postedData["recipientname"][$key];
					}

				}else{
					$error = 1;
				}
			}
			

			$saveData["event_name"] = (isset($postedData["event_name"]) && !empty($postedData["event_name"])) ? $postedData["event_name"] : $postedData["event_name"] = NULL;
			$saveData["event_date"] = (isset($postedData["event_date"]) && !empty($postedData["event_date"])) ? $postedData["event_date"] : $postedData["event_date"] = NULL;
			$saveData["description"] = (isset($postedData["description"]) && !empty($postedData["description"])) ? $postedData["description"] : $postedData["description"] = NULL;

			if($error == 0) {
				if(isset($saveData) && !empty($saveData)){	

					$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

					if ((isset($postedData['editcart']) && !empty($postedData['editcart'])) && (isset($postedData['editcartid']) && !empty($postedData['editcartid']))) {
						$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($postedData['editcartid']);
						$oldPreSavedRecepientData = json_decode($smartCartObject->getRecepientData(), true);
						if($oldPreSavedRecepientData)
						{
							foreach($oldPreSavedRecepientData as $emailId => $oldPreSavedRecepientRecord)
							{
								if(isset($recipientData[$emailId]) && (isset($oldPreSavedRecepientRecord["mailopened"])))
									$recipientData[$emailId]["mailopened"] = $oldPreSavedRecepientRecord["mailopened"];
							}
						}
					} else {
						$smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart');
					}
					$saveData["recepient_data"] = json_encode($recipientData);

					$smartCartObject->setData($saveData);
					$smartCartObject->save();
					$this->messageManager->addSuccess(__("Your Smart cart ".$smartCartObject->getTitle()." Edited Successfully."));
					$resultRedirect->setUrl($this->_redirect->getRefererUrl());
        			return $resultRedirect;
				}
			}else{
				$redirectReferer = $this->_redirect->getRefererUrl();
				if($redirectReferer){
					$redirectReferer = $this->urlModel->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
				}else{
					$redirectReferer = $this->urlModel->getUrl('customer/account/login');
				}
			}
		}else{
			$redirectReferer = $this->_redirect->getRefererUrl();
			if($redirectReferer) {
				$redirectReferer = $this->urlModel->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
			}
			else {
				$redirectReferer = $this->urlModel->getUrl('customer/account/login');
			}
		}
	}
}	
