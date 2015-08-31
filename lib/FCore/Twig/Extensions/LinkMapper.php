<?php
namespace FCore\Twig\Extensions;
use FCore\Runnable\Operation\Mapper;

class LinkMapper extends \Twig_Extension implements Preparseable
{
	const FILTER_NAME = 'getActionLink';
	
	/**
	 * @var Mapper
	 */
	private $mapper;
	private $loaded = array();
	
	public function __construct(Mapper $mapper)
	{
		$this->mapper = $mapper;
	}
	
	public function getFilters()
	{
		return array (
			self::FILTER_NAME => new \Twig_Filter_Method($this, 'getActionLink'),
		);
	}

	public function getName()
	{
		return __CLASS__;
	}

	public function getActionLink($operation)
	{
		if (isset($this->loaded[$operation])) {
			$unmapped = $this->loaded[$operation];
		} else {
			$unmapped = $this->mapper->unmapOperation($operation);
		}
		return sprintf('/%s/%s', $this->mapper->getLang(), $unmapped);
	}
	
	/* (non-PHPdoc)
	 * @see \FCore\Twig\Extensions\Preparseable::preParse()
	 */
	public function preParse($templateName)
	{
		$template = file_get_contents(\FCore\Template\Loader::getTemplateFileName($templateName));
		if (preg_match_all(sprintf('/\{\{\'(.*)\'|%s\}\}/U', self::FILTER_NAME), $template, $matches)) {
			$this->loaded += $this->mapper->unmapOperations($matches[1]);
		}
	}
}
