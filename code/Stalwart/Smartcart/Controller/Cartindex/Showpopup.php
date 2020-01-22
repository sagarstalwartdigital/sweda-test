<?php
namespace Stalwart\Smartcart\Controller\Cartindex;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;

class Showpopup extends Action
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
        $isOnlyForm = $this->getRequest()->getParam('onlyform', false);
        $isEditCart = $this->getRequest()->getParam('editcart', false);
        $isEditCartClass = $this->getRequest()->getParam('editcartclass');
        $isChooseDiffClassQuickView = $this->getRequest()->getParam('choosediffcartquickview',false);
        $isOnlySmartCartList = $this->getRequest()->getParam('onlysmartcartlist', false);

        if($isAjax)
        {
            $jsonResult = $this->_resultJsonFactory->create();
            $resultPage = $this->_resultPageFactory->create();

            if ($this->session->isLoggedIn()) {
                if($isOnlyForm || $isOnlySmartCartList || $isEditCart || $isChooseDiffClassQuickView) {
                    if ($isOnlyForm) {
                        $template = 'Stalwart_Smartcart::modal_smartcart.phtml';
                        $blockof = 'Stalwart\Smartcart\Block\ModalCart';
                    }
                    if ($isEditCart) {
                        $template = 'Stalwart_Smartcart::modal_edit_cart.phtml';
                        $blockof = 'Stalwart\Smartcart\Block\ModalCart';
                    }
                    if ($isOnlySmartCartList) {
                        $template = 'Stalwart_Smartcart::modal_only_smart_cart_list.phtml';
                        $blockof = 'Stalwart\Smartcart\Block\ModalCart';
                    }
                    if ($isChooseDiffClassQuickView) {
                        $template = 'Stalwart_Smartcart::modal_only_smart_cart_list_quick_view.phtml';
                        $blockof = 'Stalwart\Smartcart\Block\ModalCart';
                    }
                } else{
                    $template = 'Stalwart_Smartcart::modal_cart.phtml';
                    $blockof = 'Stalwart\Smartcart\Block\ModalCart';
                }
                $popupHtml = $resultPage->getLayout()
                                ->createBlock($blockof)
                                ->setTemplate($template)
                                ->setResponse($isEditCartClass)
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
