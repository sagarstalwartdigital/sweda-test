<?php
namespace Stalwart\Sweda\Controller\Startproject;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultLayoutFactory;
    /**      * @param \Magento\Framework\App\Action\Context $context      */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {

        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $jsonResult = $this->_resultJsonFactory->create();  
        $catid = $this->getRequest()->getParam('catid', false);
        // $catid = 353;
        $min = 0;
        $max=$this->getCatMaxPriceRange($catid);
        $price_array = array();
        if ($max <= 10) {
            $price_array = $this->renderRanges($this->getRanges($min,$max,6));
        }
        else if ($max <= 20) {
            $price_array = $this->renderRanges($this->getRanges($min,$max,7));
        }
        else if ($max <= 350) {
            $price_array = $this->renderRanges($this->getRanges($min,$max,8));
        }
        $jsonResult->setData(['pricerange'=> $price_array]);
        return $jsonResult;
    }

    public function getCatMaxPriceRange($catid) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');                             
        $categoryId = $catid; // YOUR CATEGORY ID
        $category = $categoryFactory->create()->load($categoryId);                             
        $categoryProducts = $category->getProductCollection()->addAttributeToSelect('*'); 
        $price= [];
        foreach ($categoryProducts as $product) {
            $price[]=$product->getData('price');
        }
        if($price){
            $max = round(max($price),2);
        }
        else{
            $max = 0;
        }       

        return $max;
    }


    public function getRanges($intMin,$intMax,$intRanges=3) {

        $intRange = $intMax-$intMin;
        $intIncrement = abs($intRange/$intRanges);
        $arrRanges = array();

        for($i=0;$i<$intRanges;$i++) {
            $arrRanges[] = $i==0 || $i==($intRanges-1)?$i==0?$intMin:$intMax:$intMin+($i*$intIncrement);
        }

        return $arrRanges;

    }

    public function renderRanges($arrRanges,$strSelected='',$strName='ranges') {
        $final_price = array();
        foreach($arrRanges as $intIndex=>$intRange) {
            if ($intIndex!=0) {
                $intMin = $intIndex == 0?$intRange:$arrRanges[($intIndex-1)];
                $intMax = $intIndex == 0?$arrRanges[($intIndex+1)]:$intRange;
                $final_price[] = array('id'=>'price='.intval($intMin).'-'.intval($intMax),'val'=>('$'.number_format((int)$intMin, 2, '.', '').'-$'.number_format((int)$intMax, 2, '.', '')));
                
            }
        }
        return $final_price;
    }
}