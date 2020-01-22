<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Vlibrary
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Vlibrary\Controller\Adminhtml\Category;

use Mageplaza\Vlibrary\Controller\Adminhtml\Category;

/**
 * Class Delete
 * @package Mageplaza\Vlibrary\Controller\Adminhtml\Category
 */
class Delete extends Category
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $this->categoryFactory->create()
                    ->load($id)
                    ->delete();

                $this->messageManager->addSuccess(__('The Vlibrary Category has been deleted.'));

                $resultRedirect->setPath('mageplaza_vlibrary/*/');

                return $resultRedirect;
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('mageplaza_vlibrary/*/edit', ['id' => $id]);

                return $resultRedirect;
            }
        }

        // display error message
        $this->messageManager->addError(__('Vlibrary Category to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('mageplaza_vlibrary/*/');

        return $resultRedirect;
    }
}
