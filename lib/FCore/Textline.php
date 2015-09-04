<?php
namespace FCore;

class Textline extends \FCore\Entity
{
	private $translation;
	
	public function getTranslation()
	{
		return $this->translation;
	}
	
	public function setTranslation($translation)
	{
		$this->translation = (string) $translation;
	}
}
