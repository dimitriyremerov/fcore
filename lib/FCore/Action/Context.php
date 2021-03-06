<?php
namespace FCore\Action;

class Context extends \FCore\Entity
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
