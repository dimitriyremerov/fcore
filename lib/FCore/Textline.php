<?php
namespace FCore;

class Textline extends \FCore\DomainObject
{
	private $translation;
	
	public function getTranslation()
	{
		return $this->translation;
	}
}
