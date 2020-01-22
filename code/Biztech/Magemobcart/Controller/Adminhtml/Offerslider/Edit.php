<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Bannerslider
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Biztech\Magemobcart\Controller\Adminhtml\Offerslider;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit Banner action.
 * @category Magestore
 * @package  Magestore_Bannerslider
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class Edit extends Action
{
    protected $offersliderModel;
    protected $resultPageFactory;
    protected $registry;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Biztech\Magemobcart\Model\Offerslider $offersliderModel,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_offersliderModel = $offersliderModel;
        $this->_registry = $registry;
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $storeViewId = $this->getRequest()->getParam('store');
        $model = $this->_offersliderModel;
        if ($id) {
            $model->setStoreViewId($storeViewId)->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This offer slider has no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $registryObject = $this->_registry;
        $registryObject->register('magemobcart_offerslider_data', $model);
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Biztech_Magemobcart::offerslider');
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? __('Edit Offer Slider') : __('Add Offer Slider'));
        return $resultPage;
    }
}
