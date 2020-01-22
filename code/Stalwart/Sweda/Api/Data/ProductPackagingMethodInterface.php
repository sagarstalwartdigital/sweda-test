<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductPackagingMethodInterface
{
	const CODE = 'code';
	const ID = 'id';
	const PACKAGINGMETHOD_ID = 'packagingmethod_id';
	const PRODUCT_ID = 'product_id';
	const RATE = 'rate';

	public function getCode(); 
	public function setCode($data); 

	public function getId(); 
	public function setId($data); 

	public function getPackagingmethodId(); 
	public function setPackagingmethodId($data); 

	public function getProductId(); 
	public function setProductId($data); 

	public function getRate(); 
	public function setRate($data); 
}