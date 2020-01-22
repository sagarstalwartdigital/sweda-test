<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductPriceRegularInterface
{
	const CODE = 'code';
	const ID = 'id';
	const MAX_QTY = 'max_qty';
	const MIN_QTY = 'min_qty';
	const PRICE_TYPE = 'price_type';
	const PRODUCT_ID = 'product_id';
	const RATE = 'rate';

	public function getCode(); 
	public function setCode($data); 

	public function getId(); 
	public function setId($data); 

	public function getMaxQty(); 
	public function setMaxQty($data); 

	public function getMinQty(); 
	public function setMinQty($data); 

	public function getPriceType(); 
	public function setPriceType($data); 

	public function getProductId(); 
	public function setProductId($data); 

	public function getRate(); 
	public function setRate($data); 
}