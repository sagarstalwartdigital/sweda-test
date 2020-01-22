<?php
namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;

class Minicartcounts extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Session
     */
    protected $urlFactory;
 
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, Session $customerSession, UrlInterface $urlFactory, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory)
    {
        $this->urlFactory = $urlFactory;
        $this->session = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
 
        parent::__construct($context);
    }


    /**
     * Confirm customer account by id and confirmation key
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $jsonResult = $this->_resultJsonFactory->create();
        $smartCartItemCount = 0;
        if ($this->session->isLoggedIn()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $smartCartItemCount = $objectManager->create('Stalwart\Smartcart\Block\SmartCartDetail')->getMiniSmartCartItemCount();
        }
        $jsonResult->setData(['logged'  => true,'smartCartItemCount' => $smartCartItemCount]);
        return $jsonResult;
        exit;
    }
}
