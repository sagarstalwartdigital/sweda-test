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

namespace Mageplaza\Vlibrary\Controller\Adminhtml\Tag;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\Vlibrary\Model\TagFactory;

/**
 * Class InlineEdit
 * @package Mageplaza\Vlibrary\Controller\Adminhtml\Tag
 */
class InlineEdit extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $jsonFactory;

    /**
     * @var \Mageplaza\Vlibrary\Model\TagFactory
     */
    public $tagFactory;

    /**
     * InlineEdit constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Mageplaza\Vlibrary\Model\TagFactory $tagFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        TagFactory $tagFactory
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->tagFactory = $tagFactory;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && !empty($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error'    => true,
            ]);
        }

        $key = array_keys($postItems);
        $tagId = !empty($key) ? (int) $key[0] : '';
        /** @var \Mageplaza\Vlibrary\Model\Tag $tag */
        $tag = $this->tagFactory->create()->load($tagId);
        try {
            $tag->addData($postItems[$tagId])
                ->save();
        } catch (LocalizedException $e) {
            $messages[] = $this->getErrorWithTagId($tag, $e->getMessage());
            $error = true;
        } catch (\RuntimeException $e) {
            $messages[] = $this->getErrorWithTagId($tag, $e->getMessage());
            $error = true;
        } catch (\Exception $e) {
            $messages[] = $this->getErrorWithTagId($tag, __('Something went wrong while saving the Tag.'));
            $error = true;
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error'    => $error
        ]);
    }

    /**
     * Add Tag id to error message
     *
     * @param \Mageplaza\Vlibrary\Model\Tag $tag
     * @param string $errorText
     *
     * @return string
     */
    public function getErrorWithTagId(\Mageplaza\Vlibrary\Model\Tag $tag, $errorText)
    {
        return '[Tag ID: ' . $tag->getId() . '] ' . $errorText;
    }
}
