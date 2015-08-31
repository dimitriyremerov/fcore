<?php
namespace FCore\Page\Element;

abstract class Input
{
	/**
	 * @var string
	 */
	private $lang;
	/**
	 * @var \FCore\User
	 */
	private $user;
	
	public function __construct($lang, \FCore\User $user = null)
	{
		$this->lang = (string) $lang;
		$this->user = $user;
	}

	public function getLang()
	{
		return $this->lang;
	}
	
	public function getUser()
	{
		return $this->user;
	}
}
