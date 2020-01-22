<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductImprintMethodSimpleChargeInterface
{
	const CODE = 'code';
	const ID = 'id';
	const IMPRINTMETHOD_CHARGE_ID = 'imprintmethod_charge_id';
	const PRODUCT_IMPRINTMETHOD_ID = 'product_imprintmethod_id';
	const RATE = 'rate';

	public function getCode(); 
	public function setCode($data); 

	public function getId(); 
	public function setId($data); 

	public function getImprintmethodChargeId(); 
	public function setImprintmethodChargeId($data); 

	public function getProductImprintmethodId(); 
	public function setProductImprintmethodId($data); 

	public function getRate(); 
	public function setRate($data); 
}