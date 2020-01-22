<?php
namespace Stalwart\Sweda\Controller\Downloadimages;

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
        $productId = $this->getRequest()->getParam('productid', false);

        if($isAjax)
        {
            $jsonResult = $this->_resultJsonFactory->create();
            $resultPage = $this->_resultPageFactory->create();

            $template = 'Stalwart_Sweda::downloadimagesmodal.phtml';
            $blockof = 'Magento\Framework\View\Element\Template';
            $popupHtml = $resultPage->getLayout()
                            ->createBlock($blockof)
                            ->setTemplate($template)
                            ->setProductId($productId)
                            ->toHtml();
            $jsonResult->setData(['popuphtml' => $popupHtml]);
        }
        return $jsonResult;
        exit;
    }
}
