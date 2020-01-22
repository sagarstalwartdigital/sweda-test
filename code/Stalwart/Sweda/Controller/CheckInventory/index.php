<?php

namespace Stalwart\Sweda\Controller\CheckInventory;

use Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;


class Index extends Action
{
   
    protected $resultPageFactory;
    protected $resultJsonFactory;

    /**
    * @var Session
    */
    protected $session;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        JsonFactory $resultJsonFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $customerSession;
        $this->stockItem = $stockItem;
        parent::__construct($context);
    }

    public function execute()
    {

        $jsonResult = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $qty_available  =(int) $this->getRequest()->getPost('qty_available');
        $product_id  = $this->getRequest()->getPost('product_id');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $configurableProduct = $objectManager->get('Magento\Catalog\Model\ProductRepository')->getById($product_id);
        $children = $configurableProduct->getTypeInstance()->getUsedProducts($configurableProduct);
        $data = $configurableProduct->getTypeInstance()->getConfigurableOptions($configurableProduct);

        

        $JsonResponse = [];
        $rates = [];
        $customCodes = array();
        $options = array();
        $rate = [];
        
        foreach ($data as $attr) {
            foreach ($attr as $product) {
                $options[$product['sku']][$product['attribute_code']] = $product['option_title'];
            }
        }
        $qtyStk = false;
        $isLogged = false;
        foreach ($children as $child){
            $swatchAttribs = $objectManager->get('Magento\Swatches\Helper\Data')->getSwatchesByOptionsId(array($child['color']));

            $rate['color_code'] = (isset($swatchAttribs[$child['color']]['value']) && !empty($swatchAttribs[$child['color']]['value'])) ? $swatchAttribs[$child['color']]['value'] : '';
            $rate['color_name'] =$child->getResource()->getAttribute('color')->getFrontend()->getValue($child);
            $rate['qty'] = $this->stockItem->getStockQty($child->getId());


            
            if ($this->stockItem->getStockQty($child->getId()) == 0 || $this->stockItem->getStockQty($child->getId()) < $qty_available) {
                $qtyStk = true;
            }
            $rate['sku'] = $child->getSku();
            
            array_push($rates, $rate);
        }


        if ($this->session->isLoggedIn()) {
            $JsonResponse['logged'] = true;
            $isLogged = true;
        } else {
            $JsonResponse['logged'] = false;
        }

        $popupHtml = $resultPage->getLayout()
                                ->createBlock('Stalwart\Smartcart\Block\ModalCart')
                                ->setTemplate('Stalwart_Sweda::product/view/inventory-lookup-table.phtml')
                                ->setRates($rates)
                                ->setRequestedQuantity($qty_available)                                
                                ->setIsLogged($isLogged)                                                               
                                ->setIsBtob($this->session->getCustomer()->getIsBtob())                                                               
                                ->toHtml();

        $JsonResponse['popuphtml'] = $popupHtml;

        
        $JsonResponse['outstock']=$qtyStk;
        $JsonResponse['data']=$rates;
        $JsonResponse['status']='success';

        

        $response = $jsonResult->setData($JsonResponse);
        return $response;
    }

}