<?php

namespace MGS\Gallery\Controller\Adminhtml\Post;

use MGS\Gallery\Controller\Adminhtml\Gallery;

class Index extends Gallery
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MGS_Gallery::manage_post');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Posts'));
        $resultPage->addBreadcrumb(__('Gallery'), __('Gallery'));
        $resultPage->addBreadcrumb(__('Manage Posts'), __('Manage Posts'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Gallery::manage_post');
    }
}
