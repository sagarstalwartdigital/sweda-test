<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Colors;
header("Access-Control-Allow-Origin: *");
class Edit extends \Biztech\PrintingMethods\Controller\Adminhtml\Colors
{

  protected $_colorsModel;

  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    \Biztech\PrintingMethods\Model\Colors $colorsModel
  ) {
    $this->_colorsModel = $colorsModel;
    parent::__construct(
      $context, $coreRegistry, $resultForwardFactory, $resultPageFactory
    );
  }

  public function execute()
  {
    $resultPage = $this->resultPageFactory->create();
    $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
    $resultPage->getConfig()->getTitle()->prepend(__('Manage Color Counter'));

    $id = $this->getRequest()->getParam('id');
    $resultPage->getConfig()->getTitle()->prepend((__('Add Color Counter')));
    if($id)
    {
      $resultPage->getConfig()->getTitle()->prepend((__('Edit Color Counter')));
    }

    $model = $this->_colorsModel;

    if ($id) {
      $model->load($id);
      if (!$model->getId()) {
        $this->messageManager->addError(__('This item no longer exists.'));
        $this->_redirect('productdesigner/colors');
        return;
      }
    }
        // set entered data if was error when we do save
    $data = $this->_session->getPageData(true);

    if (!empty($data)) {
      $model->addData($data);
    }
    $this->_coreRegistry->register('current_biztech_productdesigner_colors', $model);

    $this->_initAction();
        //$this->_view->getLayout()->getBlock('items_items_edit');
    $this->_view->renderLayout();
  }
}
