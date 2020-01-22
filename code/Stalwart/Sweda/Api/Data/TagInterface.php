<?php

namespace Stalwart\Sweda\Api\Data;

interface TagInterface
{
	const ACTIVE = 'active';
	const ID = 'id';
	const NAME = 'name';

	public function getActive(); 
	public function setActive($data); 

	public function getId(); 
	public function setId($data); 

	public function getName(); 
	public function setName($data); 
}