<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\Guestwishlist\Controller\Index;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\ResultFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Updateall extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Wishlist\Controller\WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var MGS\Guestwishlist
     */
    protected $_helper;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $_cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $_cookieMetadataFactory;

    
    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @param Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param \MGS\Guestwishlist\Helper\Data $helper
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Validator $formKeyValidator
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \MGS\Guestwishlist\Helper\Data $helper,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        $this->_customerSession = $customerSession;
        $this->wishlistProvider = $wishlistProvider;
        $this->productRepository = $productRepository;
        $this->_helper = $helper;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    /**
     * Adding new item
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        
        $cookie = $this->_helper->getCookie(\MGS\Guestwishlist\Helper\Data::COOKIE_NAME);
                    
        $post = $this->getRequest()->getPostValue();
        if ($post && isset($post['description']) && is_array($post['description'])) {
            foreach ($post['description'] as $itemId => $description) {
                foreach ($cookie as $key => $items) {
                    if ($key == $itemId) {
                        foreach ($items as $_item) {
                            if (!isset($_item['description']) || $_item['description'] != $description) {
                                $_item['description'] = $description;
                                $newItem = $_item;
                                /* Remove cookie item */
                                $cookie = $this->removeItemById($_item['item_id'], $cookie);
                                $metadata = $this->_cookieMetadataFactory
                                    ->createPublicCookieMetadata()
                                    ->setPath('/')
                                    ->setDuration(86400);
                                $this->_cookieManager->setPublicCookie(
                                    \MGS\Guestwishlist\Helper\Data::COOKIE_NAME,
                                    serialize($cookie),
                                    $metadata
                                );
                                /* Add new item */
                                $newItemId = $this->_helper->getRandomString();
                                $cookie[$itemId][$newItemId] = $newItem;
                                $metadata = $this->_cookieMetadataFactory
                                        ->createPublicCookieMetadata()
                                        ->setPath('/')
                                        ->setDuration(86400);
                                $this->_cookieManager->setPublicCookie(
                                        \MGS\Guestwishlist\Helper\Data::COOKIE_NAME, serialize($cookie), $metadata
                                );
                            }
                        }
                    }
                }
            }
            
            $this->messageManager->addSuccess(__('All Items has been updated.'));
        }
        
        $resultRedirect->setUrl($this->_url->getUrl('guestwishlist'));
        return $resultRedirect;
    }
    
    
    /**
     * 
     * @param string $itemId
     * @return [] array of wishlist
     */
    protected function removeItemById($itemId, $wishlist) {
        if ($wishlist !== null && is_array($wishlist)) {
            foreach ($wishlist as $productId => $items) {
                foreach ($items as $key => $_item) {
                    if ($itemId == $key) {
                        unset($wishlist[$productId][$itemId]);
                        // clean empty parent
                        // unset parent if does not have any child products
                        if (empty($wishlist[$productId])) {
                            unset($wishlist[$productId]);
                        }
                    }
                }
            }
        }
        return $wishlist;
    }
}
