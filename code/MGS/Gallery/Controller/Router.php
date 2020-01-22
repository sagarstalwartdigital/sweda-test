<?php

namespace MGS\Gallery\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Url;
use MGS\Gallery\Model\Post;
use MGS\Gallery\Model\Category;
use MGS\Gallery\Helper\Data;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $eventManager;
    protected $response;
    protected $dispatched;
    protected $postCollection;
    protected $categoryCollection;
    protected $galleryHelper;
    protected $storeManager;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        ManagerInterface $eventManager,
        Category $categoryCollection,
        Post $postCollection,
        Data $galleryHelper,
        StoreManagerInterface $storeManager
    )
    {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->response = $response;
        $this->galleryHelper = $galleryHelper;
        $this->categoryCollection = $categoryCollection;
        $this->postCollection = $postCollection;
        $this->storeManager = $storeManager;
    }

    public function match(RequestInterface $request)
    {
        $galleryHelper = $this->galleryHelper;
        if (!$this->dispatched) {
            $route = $galleryHelper->getConfig('general_settings/route');
            $urlKey = trim($request->getPathInfo(), '/');
            $origUrlKey = $urlKey;
            $condition = new DataObject(['url_key' => $urlKey, 'continue' => true]);
            $this->eventManager->dispatch(
                'mgs_gallery_controller_router_match_before',
                ['router' => $this, 'condition' => $condition]
            );
            $urlKey = $condition->getUrlKey();
            if ($condition->getRedirectUrl()) {
                $this->response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            if (!$condition->getContinue()) {
                return null;
            }
            if ($urlKey == $route) {
                $request->setModuleName('gallery')
                    ->setControllerName('index')
                    ->setActionName('index');
                $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $urlKey);
                $this->dispatched = true;
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Forward',
                    ['request' => $request]
                );
            }
            $identifiers = explode('/', $urlKey);
            if (count($identifiers) == 2) {
                $identifier = $identifiers[1];
                $category = $this->categoryCollection->getCollection()
                    ->addFieldToFilter('status', array('eq' => 1))
                    ->addFieldToFilter('url_key', array('eq' => $identifier))
                    ->addStoreFilter($this->storeManager->getStore()->getId())
                    ->getFirstItem();
                if ($category && $category->getId()) {
                    $request->setModuleName('gallery')
                        ->setControllerName('category')
                        ->setActionName('view')
                        ->setParam('category_id', $category->getId());
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
                $post = $this->postCollection->getCollection()
                    ->addFieldToFilter('status', array('eq' => 1))
                    ->addFieldToFilter('url_key', array('eq' => $identifier))
                    ->addStoreFilter($this->storeManager->getStore()->getId())
                    ->getFirstItem();
                if ($post && $post->getId()) {
                    $request->setModuleName('gallery')
                        ->setControllerName('post')
                        ->setActionName('view')
                        ->setParam('post_id', $post->getId());
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
            }
            if (count($identifiers) == 3) {
                $identifier = $identifiers[2];
                $post = $this->postCollection->getCollection()
                    ->addFieldToFilter('status', array('eq' => 1))
                    ->addFieldToFilter('url_key', array('eq' => $identifier))
                    ->addStoreFilter($this->storeManager->getStore()->getId())
                    ->getFirstItem();
                if ($post && $post->getId()) {
                    $request->setModuleName('gallery')
                        ->setControllerName('post')
                        ->setActionName('view')
                        ->setParam('post_id', $post->getId());
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
            }
            $identifier = substr_replace($request->getPathInfo(), '', 0, strlen('/' . $route . '/'));
            if (substr($identifier, 0, strlen('tag/')) == 'tag/') {
                $identifier = substr_replace($identifier, '', 0, 4);
                if ($identifier != null || $identifier != '') {
                    $request->setModuleName('gallery')
                        ->setControllerName('tag')
                        ->setActionName('view')
                        ->setParam('tag', urldecode($identifier));
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
            }
        }
    }
}
