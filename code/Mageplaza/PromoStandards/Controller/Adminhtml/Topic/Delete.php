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
 * @package     Mageplaza_PromoStandards
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\PromoStandards\Controller\Adminhtml\Topic;

use Mageplaza\PromoStandards\Controller\Adminhtml\Topic;

/**
 * Class Delete
 * @package Mageplaza\PromoStandards\Controller\Adminhtml\Topic
 */
class Delete extends Topic
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $this->topicFactory->create()
                    ->load($id)
                    ->delete();

                $this->messageManager->addSuccess(__('The Topic has been deleted.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $resultRedirect->setPath('mageplaza_promostandards/*/edit', ['id' => $id]);

                return $resultRedirect;
            }
        } else {
            $this->messageManager->addError(__('Topic to delete was not found.'));
        }

        $resultRedirect->setPath('mageplaza_promostandards/*/');

        return $resultRedirect;
    }
}