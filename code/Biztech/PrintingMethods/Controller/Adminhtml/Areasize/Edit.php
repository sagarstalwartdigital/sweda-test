<?php


namespace Biztech\PrintingMethods\Controller\Adminhtml\Areasize;
header("Access-Control-Allow-Origin: *");
class Edit extends \Biztech\PrintingMethods\Controller\Adminhtml\Areasize
{

    protected $_areaSize;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Biztech\PrintingMethods\Model\Areasize $areaSize
    ) {
        $this->_areaSize = $areaSize;
        parent::__construct(
            $context, $coreRegistry, $resultForwardFactory, $resultPageFactory
        );
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Area Size'));
        $id = $this->getRequest()->getParam('id');

        $resultPage->getConfig()->getTitle()->prepend((__('Add Area Size')));
        if($id)
        {
            $resultPage->getConfig()->getTitle()->prepend((__('Edit Area Size')));
        }

        $model = $this->_areaSize;

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('productdesigner/areasize');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_session->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_biztech_productdesigner_areasize', $model);

        $this->_initAction();
        $this->_view->renderLayout();
    }
}
