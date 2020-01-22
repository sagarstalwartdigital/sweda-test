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

namespace Mageplaza\PromoStandards\Controller\Adminhtml\Author;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mageplaza\PromoStandards\Controller\Adminhtml\Author;
use Mageplaza\PromoStandards\Helper\Image;
use Mageplaza\PromoStandards\Model\AuthorFactory;

/**
 * Class Save
 * @package Mageplaza\PromoStandards\Controller\Adminhtml\Author
 */
class Save extends Author
{
    /**
     * @var \Mageplaza\PromoStandards\Helper\Image
     */
    protected $imageHelper;

    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Mageplaza\PromoStandards\Model\AuthorFactory $authorFactory
     * @param \Mageplaza\PromoStandards\Helper\Image $imageHelper
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AuthorFactory $authorFactory,
        Image $imageHelper
    ) {
        $this->imageHelper = $imageHelper;

        parent::__construct($context, $registry, $authorFactory);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data = $this->getRequest()->getPost('author')) {
            /** @var \Mageplaza\PromoStandards\Model\Author $author */
            $author = $this->initAuthor();

            $this->imageHelper->uploadImage($data, 'image', Image::TEMPLATE_MEDIA_TYPE_AUTH, $author->getImage());
            if (!empty($data)) {
                $author->addData($data);
            }

            $this->_eventManager->dispatch(
                'mageplaza_promostandards_author_prepare_save',
                ['author' => $author, 'request' => $this->getRequest()]
            );

            try {
                $author->save();

                $this->messageManager->addSuccess(__('The Author has been saved.'));
                $this->_getSession()->setData('mageplaza_promostandards_author_data', false);

                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('mageplaza_promostandards/*/edit', ['_current' => true]);
                } else {
                    $resultRedirect->setPath('mageplaza_promostandards/*/');
                }

                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Author.'));
            }

            $this->_getSession()->setData('mageplaza_promostandards_author_data', $data);

            $resultRedirect->setPath('mageplaza_promostandards/*/edit', ['_current' => true]);

            return $resultRedirect;
        }
        $resultRedirect->setPath('mageplaza_promostandards/*/');

        return $resultRedirect;
    }
}
