<?php

namespace Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod;
header("Access-Control-Allow-Origin: *");
class Edit extends \Biztech\PrintingMethods\Controller\Adminhtml\Printingmethod
{

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Printing Methods'));

        $id = $this->getRequest()->getParam('id');
        $resultPage->getConfig()->getTitle()->prepend((__('Add Printing Method')));
        if($id)
        {
            $resultPage->getConfig()->getTitle()->prepend((__('Edit Printing Method')));
        }
        $model = $this->_objectManager->create('Biztech\PrintingMethods\Model\Printingmethod');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('productdesigner/printingmethod');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_biztech_productdesigner_printingmethod', $model);
        $this->_initAction();        
        $this->_view->renderLayout();
    }
}
