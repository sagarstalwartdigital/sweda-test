<?php

namespace MGS\Gallery\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use MGS\Gallery\Helper\Data as GalleryHelper;
use MGS\Gallery\Model\Comment as CommentModel;
use Magento\Framework\Controller\ResultFactory;

class Comment extends Action
{
    protected $customerAccountManagement;
    protected $accountRedirect;
    protected $session;
    protected $storeManager;
    protected $galleryHelper;
    protected $comment;
    protected $_transportBuilder;

    public function __construct(
        Context $context,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        GalleryHelper $galleryHelper,
        CommentModel $comment,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    )
    {
        $this->session = $customerSession;
        $this->storeManager = $storeManager;
        $this->galleryHelper = $galleryHelper;
        $this->comment = $comment;
        $this->_transportBuilder = $transportBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        if (!$this->galleryHelper->getConfig('general_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        if (!$this->galleryHelper->getConfig('comment_settings/enabled')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $post = $this->getRequest()->getPostValue();
        if (!$post) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        try {
            if (!$this->galleryHelper->checkLoggedIn() && $this->galleryHelper->getConfig('comment_settings/login_required')) {
                $this->messageManager->addError(__('You must be logged in to comment.'));
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);
            $postObject->setUrl($this->_redirect->getRefererUrl());
            $error = false;
            if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['content']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }

            $comment = $this->comment;
            $comment->setData($post);
            $comment->setCreatedAt($this->_objectManager->get('Magento\Framework\Stdlib\DateTime\DateTime')->gmtDate());
            $comment->setContent($comment->getContent());
            if ($this->galleryHelper->getConfig('comment_settings/auto_approve')) {
                $comment->setStatus(1);
                $this->messageManager->addSuccess(__('Your comment has been submitted.'));
            } else {
                $comment->setStatus(2);
                $this->messageManager->addSuccess(__('Your comment has been submitted and is awaiting approval.'));
            }
            $comment->save();
            $commentId = $comment->getId();
            if ($this->galleryHelper->getConfig('comment_settings/recipient_email') != null && $comment->getStatus() == 2 && isset($commentId)) {
                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier($this->galleryHelper->getConfig('comment_settings/email_template'))
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom($this->galleryHelper->getConfig('comment_settings/sender_email_identity'))
                    ->addTo($this->galleryHelper->getConfig('comment_settings/recipient_email'))
                    ->setReplyTo($post['email'])
                    ->getTransport();
                $transport->sendMessage();
            }
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}