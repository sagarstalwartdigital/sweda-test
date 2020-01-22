<?php

/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 */

namespace Biztech\AdvancedFonts\Controller\Adminhtml\Fonts;
header("Access-Control-Allow-Origin: *");
class Edit extends \Biztech\Productdesigner\Controller\Adminhtml\Fonts {

    public function execute() {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Product Designer'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Fonts'));
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Font'));
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Add Font'));
        }
        
        $model = $this->fontsFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('productdesigner/fonts');
                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->session->getPageData(true);
        
        if (!empty($data)) {
            $model->addData($data);
 
        }
        $this->_coreRegistry->register('current_biztech_productdesigner_fonts', $model);
        $this->_initAction();
        $this->_view->renderLayout();
    }

}
