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

namespace Mageplaza\Vlibrary\Controller\Vlibrary;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\Vlibrary\Helper\Data as HelperVlibrary;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class View
 * @package Mageplaza\Vlibrary\Controller\Vlibrary
 */
class View extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @type ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var HelperVlibrary
     */
    public $helperVlibrary;


    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * View constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param HelperVlibrary $helperVlibrary
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        HelperVlibrary $helperVlibrary,
        JsonFactory $resultJsonFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->helperVlibrary = $helperVlibrary;
        $this->_resultJsonFactory = $resultJsonFactory;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $isSortBy = $this->getRequest()->getParam('sort_by_options');
        $isSearchQuery = $this->getRequest()->getParam('cat_search_query');
        $isAjax = $this->getRequest()->getParam('isajax', false);
        $urls = $this->getRequest()->getParam('vlibreryfilterbyid');

        if ($isSearchQuery && !empty($isSearchQuery) || $isSortBy && !empty($isSortBy)) {
            $jsonResult = $this->_resultJsonFactory->create();
            $resultPage = $this->resultPageFactory->create();

            $template = 'Mageplaza_Vlibrary::post/list.phtml';
            $blockof = 'Mageplaza\Vlibrary\Block\Frontend';
            $popupHtml = $resultPage->getLayout()
                            ->createBlock($blockof)
                            ->setTemplate($template)
                            ->setSearchQuery($isSearchQuery)
                            ->setSortByFieldName($isSortBy)
                            ->toHtml();
            $jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);

            return $jsonResult;
            exit;
        }
        if ($urls && !empty($urls)) {
            $idsArray = array();
            foreach ($urls as $url) {
                $strToArray = explode("?",$url);
                
                list($idsLable, $idsValue) = explode("=",$strToArray['1']);
                
                $idsArray[$idsLable][] = $idsValue;
            }
            if($isAjax)
            {
                $jsonResult = $this->_resultJsonFactory->create();
                $resultPage = $this->resultPageFactory->create();

                $template = 'Mageplaza_Vlibrary::post/list.phtml';
                $blockof = 'Mageplaza\Vlibrary\Block\Frontend';
                $popupHtml = $resultPage->getLayout()
                                ->createBlock($blockof)
                                ->setTemplate($template)
                                ->setIsForFilter(true)
                                ->setData('data',$idsArray)
                                ->toHtml();
                $jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);
                
            }
            return $jsonResult;
            exit;
        } else {
            if($isAjax && !$isSearchQuery && empty($isSearchQuery))
            {
                $jsonResult = $this->_resultJsonFactory->create();
                $resultPage = $this->resultPageFactory->create();

                $template = 'Mageplaza_Vlibrary::post/list.phtml';
                $blockof = 'Mageplaza\Vlibrary\Block\Frontend';
                $popupHtml = $resultPage->getLayout()
                                ->createBlock($blockof)
                                ->setTemplate($template)
                                ->setIsForFilter(true)
                                ->setData('data','ALL')
                                ->toHtml();
                $jsonResult->setData(['logged'  => true,'popuphtml' => $popupHtml]);
                
            }
            return $jsonResult;
            exit;
        }
    }
}
