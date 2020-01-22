<?php 

namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\Controller\ResultFactory; 

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $customerSession;
    protected $_messageManager;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

	public function __construct(
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
	    \Magento\Customer\Model\Session $customerSession,
	    \Stalwart\Smartcart\Model\SmartcartFactory $smartcartFactory,
	    \Stalwart\Smartcart\Model\SmartcartitemFactory $smartcartitemFactory,
	    \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Action\Context $context

    ) {
    	$this->customerSession = $customerSession;
    	$this->_smartcartFactory = $smartcartFactory;
    	$this->_resultJsonFactory = $resultJsonFactory;
    	$this->_resultPageFactory = $resultPageFactory;
    	$this->_smartcartitemFactory = $smartcartitemFactory;
    	$this->_messageManager = $messageManager;

        parent::__construct($context);
    } 

    public function execute()
    {
    	$jsonResult = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$id = $this->getRequest()->getParam('id');
		$smartcart = $this->getRequest()->getParam('smartcart');
		$smartcartitem = $this->getRequest()->getParam('smartcartitem');
		$smartcartitemoncartedit = $this->getRequest()->getParam('smartcartitemoncartedit');
		$smartcartondetail = $this->getRequest()->getParam('smartcartondetail');

		$smartCartGridHtml = '';	
		$miniSmartCartHtml = '';
		$smartCartDetailHtml = '';	
		if ($id) {
			if ($smartcart) {
				try {
		            if ($this->customerSession->isLoggedIn()) {
		            	$model = $this->_smartcartFactory->create();
		            	
			            $model->load($id);
			            if ($model->getCustomerId() == $this->customerSession->getCustomer()->getId()) {
			            	$model->delete();
			            	$this->messageManager->addSuccess(__("Smart cart deleted successfully."));
			            }
				        $smartCartGridHtml = $resultPage->getLayout()
				            ->createBlock('Stalwart\Smartcart\Block\SmartCartFrontend')
				            ->setTemplate('Stalwart_Smartcart::smartcartdisplayfront.phtml')
				            //->setData('data',$dataYouWantToPassToBlock)
				            ->toHtml();
		            }
		        } catch (\Exception $e) {
		            $this->_messageManager->addError($e->getMessage());
		        }
			}

			if ($smartcartitem) {
				try {
		            $model = $this->_smartcartitemFactory->create();
		            $model->load($id);

					$modelSmartCart = $this->_smartcartFactory->create()->load($model->getSmartCartId());
					if ($modelSmartCart) {
						if ($modelSmartCart->getCustomerId() == $this->customerSession->getCustomer()->getId()) {
				           	$model->delete();
		            		$this->messageManager->addSuccess(__("Smart cart item deleted successfully."));
				        } 
					}
		        } catch (\Exception $e) {
		            $this->_messageManager->addError($e->getMessage());
		        }
			}
			if ($smartcartondetail) {
				try {
		            if ($this->customerSession->isLoggedIn()) {
		            	$model = $this->_smartcartFactory->create();
		            	
			            $model->load($id);
			            if ($model->getCustomerId() == $this->customerSession->getCustomer()->getId()) {
			            	$resultRedirect = $this->resultRedirectFactory->create();
							$resultRedirect->setPath('smartcart/cartindex/smartcartfront/');
			            	$model->delete();
			            	$this->messageManager->addSuccess(__("Smart cart deleted successfully."));
			            	return $resultRedirect;
			            }
		            }
		        } catch (\Exception $e) {
		            $this->_messageManager->addError($e->getMessage());
		        }
			}
			if ($smartcartitemoncartedit){
				try {
		            $model = $this->_smartcartitemFactory->create();
		            $model->load($id);

					$modelSmartCart = $this->_smartcartFactory->create()->load($model->getSmartCartId());
					if ($modelSmartCart) {
						if ($modelSmartCart->getCustomerId() == $this->customerSession->getCustomer()->getId()) {
				           	$model->delete();
		            		$this->messageManager->addSuccess(__("Smart cart item deleted successfully."));
				        } 
					}
		        } catch (\Exception $e) {
		            $this->_messageManager->addError($e->getMessage());
		        }
				$resultRedirect->setUrl($this->_redirect->getRefererUrl());
        		return $resultRedirect;
			}

			$miniSmartCartHtml = $resultPage->getLayout()
	            ->createBlock('Stalwart\Smartcart\Block\SmartCartDetail')
	            ->setTemplate('Stalwart_Smartcart::yoursmartcart.phtml')
	            //->setData('data',$dataYouWantToPassToBlock)
	            ->toHtml();

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	        $smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();

		}
		$jsonResult->setData(["smartCartGridHtml"  => $smartCartGridHtml, "miniSmartCartHtml" => $miniSmartCartHtml, 'smartCartItemCount' => $smartCartItemCount]);
		return $jsonResult;
        exit;
        //$resultRedirect->setUrl($this->_redirect->getRefererUrl());
        //return $resultRedirect;
    }
}	
