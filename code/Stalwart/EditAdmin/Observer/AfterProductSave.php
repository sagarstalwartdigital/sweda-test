<?php
/**
* Copyright � 2016 Magento. All rights reserved.
* See COPYING.txt for license details.
*/

namespace Stalwart\EditAdmin\Observer;

use Magento\Framework\Event\ObserverInterface;
 
class BeforeProductSave implements ObserverInterface
{

    /**
     * Http Request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;


    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->request = $request;
    }


    /**
     *
     *  @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $product = $observer->getProduct();

        if ((!empty($product))) {
            $productId = $product->getId();
            $postData = $this->request->getPost();
        }
    } 
 
}