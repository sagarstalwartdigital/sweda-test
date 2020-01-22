<?php

namespace MGS\Gallery\Controller\Adminhtml\Post;

use MGS\Gallery\Controller\Adminhtml\Gallery;

class Grid extends Gallery
{
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
