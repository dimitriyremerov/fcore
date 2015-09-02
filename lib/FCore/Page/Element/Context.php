<?php
namespace FCore\Page\Element;

abstract class Context extends \FCore\DomainObject
{
	/**
	 * @todo Think about moving this out
	 * @var string
	 */
	protected $lang;
	
	public function __construct($lang)
	{
		$this->setLang($lang);
	}
	
	public function setLang($lang)
	{
		$this->lang = (string) $lang;
	}
}
