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

namespace Mageplaza\PromoStandards\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mageplaza\PromoStandards\Model\PostFactory;

/**
 * Class Post
 * @package Mageplaza\PromoStandards\Controller\Adminhtml
 */
abstract class Post extends Action
{
    /** Authorization level of a basic admin session */
    const ADMIN_RESOURCE = 'Mageplaza_PromoStandards::post';

    /**
     * Post Factory
     *
     * @var \Mageplaza\PromoStandards\Model\PostFactory
     */
    public $postFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;

    /**
     * Post constructor.
     *
     * @param \Mageplaza\PromoStandards\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        PostFactory $postFactory,
        Registry $coreRegistry,
        Context $context
    ) {
        $this->postFactory = $postFactory;
        $this->coreRegistry = $coreRegistry;

        parent::__construct($context);
    }

    /**
     * @param bool $register
     *
     * @return bool|\Mageplaza\PromoStandards\Model\Post
     */
    protected function initPost($register = false)
    {
        $postId = (int) $this->getRequest()->getParam('id');

        /** @var \Mageplaza\PromoStandards\Model\Post $post */
        $post = $this->postFactory->create();
        if ($postId) {
            $post->load($postId);
            if (!$post->getId()) {
                $this->messageManager->addErrorMessage(__('This post no longer exists.'));

                return false;
            }
        }

        if (!$post->getAuthorId()) {
            $post->setAuthorId($this->_auth->getUser()->getId());
        }

        if ($register) {
            $this->coreRegistry->register('mageplaza_promostandards_post', $post);
        }

        return $post;
    }
}
