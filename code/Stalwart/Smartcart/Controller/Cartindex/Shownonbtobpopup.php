<?php
namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;

class Shownonbtobpopup extends Action
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
        $isAjax = $this->getRequest()->getParam('isajax', false);

        if($isAjax)
        {
            $jsonResult = $this->_resultJsonFactory->create();
            $resultPage = $this->_resultPageFactory->create();

            if ($this->session->isLoggedIn()) {
                
                $template = 'Stalwart_Smartcart::modal_non_btob.phtml';
                $blockof = 'Stalwart\Smartcart\Block\ModalCart';
                $popupHtml = $resultPage->getLayout()
                                ->createBlock($blockof)
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
        }
        return $jsonResult;
        exit;
    }
}
