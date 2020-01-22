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

namespace Mageplaza\PromoStandards\Controller\Adminhtml\Comment;

use Magento\Framework\Exception\LocalizedException;
use Mageplaza\PromoStandards\Controller\Adminhtml\Comment;
use Mageplaza\PromoStandards\Model\CommentFactory;

/**
 * Class Save
 * @package Mageplaza\PromoStandards\Controller\Adminhtml\Comment
 */
class Save extends Comment
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPost('comment')) {
            /** @var \Mageplaza\PromoStandards\Model\Comment $post */
            $comment = $this->initComment();

            $this->prepareData($comment, $data);

            $this->_eventManager->dispatch(
                'mageplaza_promostandards_comment_prepare_save',
                ['comment' => $comment, 'request' => $this->getRequest()]
            );

            try {
                $comment->save();

                $this->messageManager->addSuccess(__('The comment has been saved.'));
                $this->_getSession()->setData('mageplaza_promostandards_comment_data', false);

                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('mageplaza_promostandards/*/edit', ['id' => $comment->getId(), '_current' => true]);
                } else {
                    $resultRedirect->setPath('mageplaza_promostandards/*/');
                }

                return $resultRedirect;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Comment.'));
            }

            $this->_getSession()->setData('mageplaza_promostandards_comment_data', $data);

            $resultRedirect->setPath('mageplaza_promostandards/*/edit', ['id' => $comment->getId(), '_current' => true]);

            return $resultRedirect;
        }

        $resultRedirect->setPath('mageplaza_promostandards/*/');

        return $resultRedirect;
    }

    /**
     * @param $comment
     * @param array $data
     *
     * @return $this
     */
    protected function prepareData($comment, $data = [])
    {
        $comment->addData($data);

        return $this;
    }
}
