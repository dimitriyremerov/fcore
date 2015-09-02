<?php
namespace FCore\Twig\Extensions;
/**
 * Provides filters functions and methods to Twig
 */
class General extends \Twig_Extension
{
	public function getFilters()
	{
		return array (
			'date' => new \Twig_Filter_Method($this, 'dateFormat'),
			'dateTime' => new \Twig_Filter_Method($this, 'dateTimeFormat'),
		);
	}
	
	public function getName()
	{
		return 'FCore_General';
	}
	
	public function dateFormat($time)
	{
		return date('d M Y', $time);
	}
	
	public function dateTimeFormat($time)
	{
		return date('d M Y H:i', $time);
	}
}
