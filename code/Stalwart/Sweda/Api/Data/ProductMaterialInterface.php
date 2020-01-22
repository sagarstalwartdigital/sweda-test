<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductMaterialInterface
{
	const ID = 'id';
	const MATERIAL_ID = 'material_id';
	const PRODUCT_ID = 'product_id';

	public function getId(); 
	public function setId($data); 

	public function getMaterialId(); 
	public function setMaterialId($data); 

	public function getProductId(); 
	public function setProductId($data); 
}