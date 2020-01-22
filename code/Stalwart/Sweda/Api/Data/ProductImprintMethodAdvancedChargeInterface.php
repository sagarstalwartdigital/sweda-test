<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductImprintMethodAdvancedChargeInterface
{
	const CODE = 'code';
	const END_QTY = 'end_qty';
	const ID = 'id';
	const IMPRINTMETHOD_CHARGE_ID = 'imprintmethod_charge_id';
	const PRODUCT_IMPRINTMETHOD_ID = 'product_imprintmethod_id';
	const RATE = 'rate';
	const START_QTY = 'start_qty';

	public function getCode(); 
	public function setCode($data); 

	public function getEndQty(); 
	public function setEndQty($data); 

	public function getId(); 
	public function setId($data); 

	public function getImprintmethodChargeId(); 
	public function setImprintmethodChargeId($data); 

	public function getProductImprintmethodId(); 
	public function setProductImprintmethodId($data); 

	public function getRate(); 
	public function setRate($data); 

	public function getStartQty(); 
	public function setStartQty($data); 
}