<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductExtraInterface
{
	const COUNTRY = 'country';
	const CURRENCY = 'currency';
	const DISTRIBUTOR_CENTRAL_URL = 'distributor_central_url';
	const LANGUAGE = 'language';
	const PRODUCT_ID = 'product_id';
	const PRODUCTION_TEMPLATE = 'production_template';
	const PROP65_STATUS = 'prop65_status';
	const SPECIAL_PRICE_TILL = 'special_price_till';
	const VALID_UPTO = 'valid_upto';
	const VAT = 'vat';
	const VAT_UNIT = 'vat_unit';
	const VIDEO_URL = 'video_url';

	public function getCountry(); 
	public function setCountry($data); 

	public function getCurrency(); 
	public function setCurrency($data); 

	public function getDistributorCentralUrl(); 
	public function setDistributorCentralUrl($data); 

	public function getLanguage(); 
	public function setLanguage($data); 

	public function getProductId(); 
	public function setProductId($data); 

	public function getProductionTemplate(); 
	public function setProductionTemplate($data); 

	public function getProp65Statu(); 
	public function setProp65Statu($data); 

	public function getSpecialPriceTill(); 
	public function setSpecialPriceTill($data); 

	public function getValidUpto(); 
	public function setValidUpto($data); 

	public function getVat(); 
	public function setVat($data); 

	public function getVatUnit(); 
	public function setVatUnit($data); 

	public function getVideoUrl(); 
	public function setVideoUrl($data); 
}