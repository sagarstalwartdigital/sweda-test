<?php 

namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class SetActiveCart extends \Magento\Framework\App\Action\Action
{
	protected $customerSession;
	protected $_messageManager;
	protected $resultRedirect;
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
		\Magento\Framework\Message\ManagerInterface $messageManager,
		ResultFactory $result,
		PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
		\Magento\Framework\App\Action\Context $context

	) {
		$this->customerSession = $customerSession;
		$this->_messageManager = $messageManager;
		$this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
		$this->resultRedirect = $result;

		parent::__construct($context);
	} 

	public function execute() {

		$isAjax = $this->getRequest()->getParam('isajax', false);
		$isLocation = $this->getRequest()->getParam('locationtosend', false);
		$isSmartCartId = $this->getRequest()->getParam('smartcartid', false);
        
        $jsonResult = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        
        if($isAjax || $isLocation)
        {
        	if (!$isLocation) {
        		if ($this->customerSession->isLoggedIn()) {
	                $template = 'Stalwart_Smartcart::modal_add_more_products.phtml';
	                $popupHtml = $resultPage->getLayout()
	                                ->createBlock('MGS\Mmegamenu\Block\Mmegamenu')
	                                ->setTemplate($template)
	                                ->toHtml();
	                $jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);
	            }else{
	                $redirectReferer = $this->getRequest()->getParam('currenturl');
	                if($redirectReferer)
	                    $redirectReferer = $this->urlFactory->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
	                else
	                    $redirectReferer = $this->urlFactory->getUrl('customer/account/login');
	                $jsonResult->setData(['logged'  => false, "redirectUrl" => $redirectReferer]);
	            }
	            return $jsonResult;
	            exit;
        	} else {
        		if ($this->customerSession->isLoggedIn()) {
	                $template = 'Stalwart_Smartcart::modal_add_more_products.phtml';

	                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	                $smartCartObject = $objectManager->create('Stalwart\Smartcart\Model\Smartcart')->load($isSmartCartId);
					$smartCartObject->setUpdatedAt(date('Y-m-d G:i:s'));
					$smartCartObject->save();

	                $catUrl = $isLocation;
	                $jsonResult->setData(['logged'  => true,'catUrl' => $catUrl]);
	            }else{
	                $redirectReferer = $this->getRequest()->getParam('currenturl');
	                if($redirectReferer)
	                    $redirectReferer = $this->urlFactory->getUrl('customer/account/login',array('referer' => base64_encode($redirectReferer)));
	                else
	                    $redirectReferer = $this->urlFactory->getUrl('customer/account/login');
	                $jsonResult->setData(['logged'  => false, "redirectUrl" => $redirectReferer]);
	            }
	            return $jsonResult;
	            exit;
        	}
	            
        }
		
	}	
}
