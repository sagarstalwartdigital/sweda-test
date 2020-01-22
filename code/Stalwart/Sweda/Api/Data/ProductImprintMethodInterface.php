<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductImprintMethodInterface
{
	const FULL_COLOR = 'full_color';
	const ID = 'id';
	const IMPRINTMETHOD_ID = 'imprintmethod_id';
	const LOCATION_PRICE_INCLUDED = 'location_price_included';
	const MAX_COLORS_ALLOWED = 'max_colors_allowed';
	const MAX_LOCATION_ALLOWED = 'max_location_allowed';
	const PMS_COLOR_ALLOWED = 'pms_color_allowed';
	const PRICE_INCLUDED = 'price_included';
	const PRODUCT_ID = 'product_id';
	const PRODUCTION_DAYS = 'production_days';
	const PRODUCTION_UNIT = 'production_unit';

	public function getFullColor(); 
	public function setFullColor($data); 

	public function getId(); 
	public function setId($data); 

	public function getImprintmethodId(); 
	public function setImprintmethodId($data); 

	public function getLocationPriceIncluded(); 
	public function setLocationPriceIncluded($data); 

	public function getMaxColorsAllowed(); 
	public function setMaxColorsAllowed($data); 

	public function getMaxLocationAllowed(); 
	public function setMaxLocationAllowed($data); 

	public function getPmsColorAllowed(); 
	public function setPmsColorAllowed($data); 

	public function getPriceIncluded(); 
	public function setPriceIncluded($data); 

	public function getProductId(); 
	public function setProductId($data); 

	public function getProductionDay(); 
	public function setProductionDay($data); 

	public function getProductionUnit(); 
	public function setProductionUnit($data); 
}