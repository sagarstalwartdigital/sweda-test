<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductShippingInfoInterface
{
	const CARTON_CAPACITY = 'carton_capacity';
	const CARTON_HEIGHT = 'carton_height';
	const CARTON_LENGTH = 'carton_length';
	const CARTON_SIZE_UNIT = 'carton_size_unit';
	const CARTON_WEIGHT = 'carton_weight';
	const CARTON_WEIGHT_UNIT = 'carton_weight_unit';
	const CARTON_WIDTH = 'carton_width';
	const FOB_COUNTRY = 'fob_country';
	const FOB_STATE = 'fob_state';
	const FOB_ZIPCODE = 'fob_zipcode';
	const ID = 'id';
	const PRODUCT_HEIGHT = 'product_height';
	const PRODUCT_ID = 'product_id';
	const PRODUCT_LENGTH = 'product_length';
	const PRODUCT_SIZE_UNIT = 'product_size_unit';
	const PRODUCT_WEIGHT = 'product_weight';
	const PRODUCT_WEIGHT_UNIT = 'product_weight_unit';
	const PRODUCT_WIDTH = 'product_width';

	public function getCartonCapacity(); 
	public function setCartonCapacity($data); 

	public function getCartonHeight(); 
	public function setCartonHeight($data); 

	public function getCartonLength(); 
	public function setCartonLength($data); 

	public function getCartonSizeUnit(); 
	public function setCartonSizeUnit($data); 

	public function getCartonWeight(); 
	public function setCartonWeight($data); 

	public function getCartonWeightUnit(); 
	public function setCartonWeightUnit($data); 

	public function getCartonWidth(); 
	public function setCartonWidth($data); 

	public function getFobCountry(); 
	public function setFobCountry($data); 

	public function getFobState(); 
	public function setFobState($data); 

	public function getFobZipcode(); 
	public function setFobZipcode($data); 

	public function getId(); 
	public function setId($data); 

	public function getProductHeight(); 
	public function setProductHeight($data); 

	public function getProductId(); 
	public function setProductId($data); 

	public function getProductLength(); 
	public function setProductLength($data); 

	public function getProductSizeUnit(); 
	public function setProductSizeUnit($data); 

	public function getProductWeight(); 
	public function setProductWeight($data); 

	public function getProductWeightUnit(); 
	public function setProductWeightUnit($data); 

	public function getProductWidth(); 
	public function setProductWidth($data); 
}