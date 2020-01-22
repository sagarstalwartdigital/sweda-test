<?php

namespace MGS\Gallery\Controller\Adminhtml\Category;

use MGS\Gallery\Controller\Adminhtml\Gallery;

class Index extends Gallery
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MGS_Gallery::manage_category');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Categories'));
        $resultPage->addBreadcrumb(__('Gallery'), __('Gallery'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Categories'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Gallery::manage_category');
    }
}
