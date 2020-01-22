<?php

namespace MGS\Gallery\Controller\Adminhtml\Post;

use MGS\Gallery\Controller\Adminhtml\Gallery;

class Edit extends Gallery
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');
        $model = $this->_objectManager->create('MGS\Gallery\Model\Post');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                $this->_redirect('gallery/post/index');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_post', $model);
        $this->_initAction()->_addBreadcrumb(
            $id ? __('Edit Post') : __('Add New Post'),
            $id ? __('Edit Post') : __('Add New Post')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Gallery'));
        $this->_view->getPage()->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add New Post'));
        $this->_view->getLayout()->getBlock('post_edit');
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Gallery::edit_post');
    }
}
