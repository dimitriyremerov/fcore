<?php
namespace FCore\Page;
use FCore\Page\Request;
use FCore\Page\Element\Input as AbstractInput;

abstract class Input extends AbstractInput
{
	/**
	 * @var Request
	 */
	protected $pageRequest;
	
	public function __construct($lang, \FCore\User $user = null, Request $pageRequest)
	{
		parent::__construct($lang, $user);
		$this->pageRequest = $pageRequest;
	}
	/**
	 * @return Request
	 */
	public function getPageRequest()
	{
		return $this->pageRequest;
	}
}
