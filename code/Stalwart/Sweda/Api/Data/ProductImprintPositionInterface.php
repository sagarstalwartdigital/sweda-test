<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductImprintPositionInterface
{
	const ID = 'id';
	const POSITION_ID = 'position_id';
	const PRODUCT_IMPRINTMETHOD_ID = 'product_imprintmethod_id';

	public function getId(); 
	public function setId($data); 

	public function getPositionId(); 
	public function setPositionId($data); 

	public function getProductImprintmethodId(); 
	public function setProductImprintmethodId($data); 
}