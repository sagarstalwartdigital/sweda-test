<?php

namespace MGS\Gallery\Controller\Adminhtml\Category;

class NewAction extends \MGS\Gallery\Controller\Adminhtml\Gallery
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Gallery::edit_category');
    }
}
