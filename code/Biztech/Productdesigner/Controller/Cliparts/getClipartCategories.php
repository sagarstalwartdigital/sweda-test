<?php

/**
 * Copyright © 2017-2018 AppJetty. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Biztech\Productdesigner\Controller\Cliparts;
ini_set('display_startup_errors', 1);

header("Access-Control-Allow-Origin: *");

class getClipartCategories extends \Biztech\Productdesigner\Controller\Cliparts {

    const Identifier = 'getClipartCategories';
    const patternIdentifier = 'getBackGroundPatterns';

    public function execute() {
        try {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $product_Id = $data['product_id'];
            $limit = $data['limit'];
            $page = $data['page'];
            $type = $data['type'];
            $cacheKey = self::Identifier. $product_Id;
            if($type == 'pattern'){
                $cacheKey = self::patternIdentifier . $product_Id;
            }
            
            $response = $this->_infoHelper->loadCache($cacheKey);
            $response = null;
            // if (!$response) {

            $storeid = $this->_storeManager->getStore()->getId();
            $response['clipArtcategories'] = $this->fetchClipartCategories($storeid, $type);
            $product=$this->_productLoader->create()->load($product_Id);
            if($product->getDefaultClipartCategory()!=""){
                $response['default_category_id']=$product->getDefaultClipartCategory();
            }else{
                $configClipartCat = $this->_pdHelper->getConfig('productdesigner/clipart_general/default_clipart_category', $storeid);
                $response['default_category_id'] = $configClipartCat;
            }
                // if clipart category is pattern or not
            foreach ($response['clipArtcategories'] as $key => $value) {
                if($value['id'] == $response['default_category_id']){
                    $response['default_category_id'] = $value['id'];
                }else{
                    $response['default_category_id'] = $response['clipArtcategories'][0]['id'];
                }
            }
            if($type == 'pattern'){
                if(count($response['clipArtcategories']) > 0){
                    $response['default_category_id'] = $response['clipArtcategories'][0]['id'];
                }
                if(count($response['clipArtcategories']) == 0){
                    $response['default_category_id'] = 0;
                }
            }
            foreach ($response['clipArtcategories'] as $key => $value) {
                if ($value['id'] == $response['default_category_id']) {
                    $response['default_clipart_label'] = $value['name'];
                }
            }

            $response['clipart_alert'] = $this->_pdHelper->getConfig('productdesigner/clipart_general/limit_alert', $storeid);
            $response['clipart_limit']= $this->_pdHelper->getConfig('productdesigner/clipart_general/clipart_limit', $storeid);
            $response['clipart_upload_limit']= $this->_pdHelper->getConfig('productdesigner/clipart_general/clipart_image_limit', $storeid);

            $clipartImages = $this->fetchClipartImages($response['default_category_id'], $limit,'',$page);
            if(count($clipartImages['images']) == 0 && count($response['clipArtcategories']) > 0){
                $clipartCat = $this->getSubCategories($response['default_category_id'], $type);
                if(count($clipartCat) > 0){
                    foreach ($clipartCat as $key => $clipart_value) {
                        $clipartImages = $this->fetchClipartImages($clipart_value['id'], $limit,'',$page);
                        if(count($clipartImages['images']) > 0){
                            $response['default_category_id'] = $clipart_value['id'];
                            $response['default_clipart_label'] = $clipart_value['name'];
                            break;
                        }
                    }
                }
            }

            $response['default_clipart_images'] = $clipartImages['images'];
            $response['priceFormat'] = $clipartImages['priceFormat']; 
            $response['loadMoreFlag'] = $clipartImages['loadMoreFlag'];
            $this->_infoHelper->setCache($response, $cacheKey);
            // }

            $this->getResponse()->setBody(json_encode($response));
        } catch (\Exception $e) {
            $response = $this->_infoHelper->throwException($e, self::class);
            $this->getResponse()->setBody(json_encode($response));
        }
    }

    public function fetchClipartCategories($storeid, $type) {
        try {
            array_push($this->allstore, 0);
            array_push($this->allstore, $storeid);
            $clipartCategoriesCollection = $this->_clipartFactory->create()
            ->addFieldToFilter("is_root_category", array("eq" => 1))
            ->addFieldToFilter("status", array("eq" => 1))
            ->addStoreFilter($this->allstore);
            $clipartCategoriesCollection->getSelect()->order('clipart_title ASC');

            // filter out
            $customObject = $this->_objectFactory->create();
            $customObject->setClipartCategories($clipartCategoriesCollection);
            $this->_eventManager->dispatch('clipart_categories_fetched_after', ['clipartCollection' => $customObject, 'type'=> $type, 'allstore' => $this->allstore]);
            $this->clipArtcategories = $customObject->getClipartCategories();
            // $this->_eventManager->dispatch('addFilterClipart', ['clipartCategoriesCollection' => $clipartCategoriesCollection, 'type' => $type]);
            // $this->clipArtcategories = [];
            // foreach ($clipartCategoriesCollection as $clipartCat) {                
            //     $this->clipArtcategories[] = array(
            //         'id' => $clipartCat->getClipartId(),
            //         'name' => $clipartCat->getClipartTitle(),
            //         'level' => 0
            //     );                
                
            //     $this->getSubCategories($clipartCat->getClipartId(), $type);
            // }
            return $this->clipArtcategories;
        } catch (\Exception $e) {
            $response = $this->_infoHelper->throwException($e, self::class);
            $this->getResponse()->setBody(json_encode($response));
        }
    }

    public function getSubCategories($parentClipartId, $type) {
        $clipartCategoriesCollection = $this->_clipartFactory->create()
                ->addFieldToFilter('parent_categories', array('eq' => $parentClipartId))
                ->addFieldToFilter('status', array('eq' => 1))
                ->addStoreFilter($this->allstore);
        $this->_eventManager->dispatch('addFilterClipart', ['clipartCategoriesCollection' => $clipartCategoriesCollection, 'type' => $type]);
        $clipartSubCatData = array();
        if ($clipartCategoriesCollection->count() > 0) {
            foreach ($clipartCategoriesCollection as $clipartCat) {
                $clipartSubCat = array(
                    'id' => $clipartCat->getClipartId(),
                    'name' => $clipartCat->getClipartTitle(),
                    'level' => $clipartCat->getLevel(),
                );
                $clipartSubCatData[] = $clipartSubCat;

                $this->clipArtcategories[] = $clipartSubCat;

                $this->getSubCategories($clipartCat->getClipartId(), $type);
            }
        }
        return $clipartSubCatData;
    }

}
