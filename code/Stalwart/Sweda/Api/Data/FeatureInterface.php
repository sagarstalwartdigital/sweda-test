<?php

namespace Stalwart\Sweda\Api\Data;

interface FeatureInterface
{
	const ID = 'id';
	const NAME = 'name';

	public function getId(); 
	public function setId($data); 

	public function getName(); 
	public function setName($data); 
}