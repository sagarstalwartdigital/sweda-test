<?php

namespace Stalwart\Sweda\Api\Data;

interface ProductTagInterface
{
	const ID = 'id';
	const PRODUCT_ID = 'product_id';
	const TAG_ID = 'tag_id';

	public function getId(); 
	public function setId($data); 

	public function getProductId(); 
	public function setProductId($data); 

	public function getTagId(); 
	public function setTagId($data); 
}