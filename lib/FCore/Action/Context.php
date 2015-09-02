<?php
namespace FCore\Action;

class Context extends \FCore\DomainObject
{
	/**
	 * @var string
	 */
	private $redirectLocation;
	
	/**
	 * @return string
	 */
	public function getRedirectLocation()
	{
		return $this->redirectLocation;
	}
	/**
	 * @param string $redirectLocation
	 */
	public function setRedirectLocation($redirectLocation)
	{
		$this->redirectLocation = (string) $redirectLocation;
	}
}
