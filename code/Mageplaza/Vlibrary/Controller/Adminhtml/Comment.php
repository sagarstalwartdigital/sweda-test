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

namespace Mageplaza\Vlibrary\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mageplaza\Vlibrary\Model\CommentFactory;

/**
 * Class Comment
 * @package Mageplaza\Vlibrary\Controller\Adminhtml
 */
abstract class Comment extends Action
{
    /** Authorization level of a basic admin session */
    const ADMIN_RESOURCE = 'Mageplaza_Vlibrary::comment';

    /**
     * @var \Mageplaza\Vlibrary\Model\CommentFactory
     */
    public $commentFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;

    /**
     * Comment constructor.
     *
     * @param CommentFactory $commentFactory
     * @param Registry $coreRegistry
     * @param Context $context
     */
    public function __construct(
        CommentFactory $commentFactory,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->commentFactory = $commentFactory;
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * @param bool $register
     *
     * @return bool|\Mageplaza\Vlibrary\Model\Comment
     */
    protected function initComment($register = false)
    {
        $cmtId = $this->getRequest()->getParam("id");

        /** @var \Mageplaza\Vlibrary\Model\Post $post */
        $comment = $this->commentFactory->create();

        if ($cmtId) {
            $comment->load($cmtId);
            if (!$comment->getId()) {
                $this->messageManager->addErrorMessage(__('This comment no longer exists.'));

                return false;
            }
        }

        if ($register) {
            $this->coreRegistry->register('mageplaza_vlibrary_comment', $comment);
        }

        return $comment;
    }
}
