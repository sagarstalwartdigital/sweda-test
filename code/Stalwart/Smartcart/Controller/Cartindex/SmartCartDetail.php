<?php
namespace Stalwart\Smartcart\Controller\Cartindex;
class SmartCartDetail extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
   
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $this->_view->loadLayout(); 
        $this->_view->renderLayout();

    }
}