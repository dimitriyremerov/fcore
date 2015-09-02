<?php
namespace FCore\Action;
use \FCore\Page;

abstract class Controller implements \FCore\Runnable
{
	protected $lang;
	/**
	 * @var \FCore\Page\Request
	 */
	protected $pageRequest;
	/**
	 * @var Context
	 */
	protected $context;
	/**
	 * @var bool
	 */
	protected $ajax = false;
	
	public function __construct($lang, Page\Request $pageRequest, $ajax = false)
	{
		$this->lang = (string) $lang;
		$this->pageRequest = $pageRequest;
		$this->context = $this->createContextObject();
		$this->ajax = (bool) $ajax;
	}

	protected function createContextObject()
	{
		return new Context();
	}

	final public function execute()
	{
		try {
			$input = $this->parseRequest($this->pageRequest);
			$this->run($input);
		} catch (Exception $exc) {
			
			$this->context->setRedirectLocation('/');
		}
		return $this;
	}
	
	abstract protected function parseRequest(Page\Request $pageRequest);

	abstract protected function run(Input $input);
	
	/* (non-PHPdoc)
	 * @see FV_Runnable::render()
	 */
	public function render()
	{
		if ($this->ajax) {
			header('Content-Type: application/json'); //FIXME
			return json_encode($this->context->expose());
		} else {
			if ($this->context->getRedirectLocation()) {
				$this->redirect($this->context->getRedirectLocation());
			}
		}
	}
	
	private function redirect($url)
	{
		header('Location: ' . $url);
	}
}
