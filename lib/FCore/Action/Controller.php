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

    /**
     * @param string $lang
     * @param Page\Request $pageRequest
     * @param bool $ajax
     */
    public function __construct(string $lang, Page\Request $pageRequest, bool $ajax = false)
	{
		$this->lang = $lang;
		$this->pageRequest = $pageRequest;
		$this->context = $this->createContextObject();
		$this->ajax = $ajax;
	}

    /**
     * @return Context
     */
    protected function createContextObject(): Context
	{
		return new Context();
	}

	final public function execute(): self
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
