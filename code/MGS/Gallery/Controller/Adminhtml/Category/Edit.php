<?php

namespace MGS\Gallery\Controller\Adminhtml\Category;

use MGS\Gallery\Controller\Adminhtml\Gallery;

class Edit extends Gallery
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $model = $this->_objectManager->create('MGS\Gallery\Model\Category');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This category no longer exists.'));
                $this->_redirect('gallery/category/index');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_category', $model);
        $this->_initAction()->_addBreadcrumb(
            $id ? __('Edit Category') : __('Add New Category'),
            $id ? __('Edit Category') : __('Add New Category')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Gallery'));
        $this->_view->getPage()->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add New Category'));
        $this->_view->getLayout()->getBlock('category_edit');
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Gallery::edit_category');
    }
}
