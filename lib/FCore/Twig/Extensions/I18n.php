<?php
namespace FCore\Twig\Extensions;
use FCore\Textline\Storage;

class I18n extends \Twig_Extension implements Preparseable
{
	const FILTER_NAME = '_';
	
	/**
	 * @var Storage
	 */
	private $storage;
	private $loaded = [];
	
	public function __construct($storage)
	{
 		$this->storage = $storage;
	}
	
	public function getFilters()
	{
		return array (
			self::FILTER_NAME => new \Twig_Filter_Method($this, 'translate'),
		);
	}
	
	public function translate($string)
	{
		if (isset($this->loaded[$string])) {
			$textline = $this->loaded[$string];
		} else {
			$data = $this->storage->loadByTexts([$string]);
			if ($data) {
				$textline = current($data);
			}
		}
		if (isset($textline)) {
			/* @var $textline \FCore\Textline */
			return $textline->getTranslation();
		}
		return $string; 
	}

	/* (non-PHPdoc)
	 * @see \FCore\Twig\Extensions\Preparseable::preParse()
	*/
	public function preParse($templateName)
	{
		$template = file_get_contents(\FCore\Template\Loader::getTemplateFileName($templateName));
		if (preg_match_all(sprintf('/\{\{\'(.*)\'|%s\}\}/U', self::FILTER_NAME), $template, $matches)) {
			$this->loaded += $this->storage->loadByTexts($matches[1]);
		}
	}

	/* (non-PHPdoc)
	 * @see Twig_ExtensionInterface::getName()
	 */
	public function getName()
	{
		return __CLASS__;
	}
}
