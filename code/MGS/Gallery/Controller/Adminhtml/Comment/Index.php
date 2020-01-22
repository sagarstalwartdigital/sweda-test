<?php

namespace MGS\Gallery\Controller\Adminhtml\Comment;

use MGS\Gallery\Controller\Adminhtml\Gallery;

class Index extends Gallery
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MGS_Gallery::manage_comment');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Comments'));
        $resultPage->addBreadcrumb(__('Gallery'), __('Gallery'));
        $resultPage->addBreadcrumb(__('Manage Categories'), __('Manage Comments'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Gallery::manage_comment');
    }
}
