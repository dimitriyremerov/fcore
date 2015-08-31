<?php
namespace FCore\Ajax;

abstract class Controller implements \FCore\Runnable
{
	/**
	 * @var \FCore\Page\Element\Context
	 */
	protected $context;
	
	
	/* (non-PHPdoc)
	 * @see FV_Runnable::render()
	 */
	public function render()
	{
		return json_encode($this->getContext());
	}
	
	public function getContext()
	{
		return $this->context;
	}
}
