<?php
namespace FCore\Page\Element;
/**
 * @author Dimitriy
 * @todo Remove dependencies on Twig
 */
abstract class Controller implements \FCore\Runnable
{
	/**
	 * @var Input
	 */
	protected $input;
	/**
	 * @var Context
	 */
	protected $context;
	/**
	 * @var array(Controller $element)
	 */
	protected $subElements = [];
	/**
	 * @var \FCore\Template\Loader
	 */
	protected $templateLoader;
	
	protected $extensions = [];

	/**
	 * @param Input $input
	 */
	public function __construct(Input $input)
	{
		$this->input = $input;
	}

	/**
	 * @return Context
	 */
	abstract public function createContextObject();
	
	abstract public function generateContext();
	
	abstract protected function getTextlinesMap();
	
	
	public function registerElement($name, Controller $element)
	{
		$this->subElements[$name] = $element;
	}
	
	/**
	 * @return Controller
	 */
	public function execute()
	{
		$this->context = $this->createContextObject();
		$this->preParse();
		$this->generateContext();
		$subElements = $this->subElements;
		foreach ($subElements as $element) {
			/* @var $element Controller */
			$element->extensions += $this->extensions;
			$element->execute();
		}
		return $this;
	}
	
	public function preParse()
	{
		$extensions = $this->extensions;
		foreach ($extensions as $extension) {
			if ($extension instanceof \FCore\Twig\Extensions\Preparseable) {
				$extension->preParse($this->getTemplateName());
			}
		}
	}
	
	/**
	 * @return string
	 */
	public function render()
	{
		$templateName = $this->getTemplateName();
		$templateLoader = $this->getTemplateLoader();
		$templateLoader->addExtensions($this->extensions);
		$template = $templateLoader->loadTemplate($templateName);
		$context = $this->exposeContext();
		return $template->render($context);
	}

	/**
	 * @return \FCore\Template\Loader
	 */
	protected function getTemplateLoader()
	{
		if (!isset($this->templateLoader)) {
			$this->templateLoader = new \FCore\Template\Loader();
		}
		return $this->templateLoader;
	}

	public function addExtensions(array $extensions)
	{
		foreach ($extensions as $key => $extension) {
			/* @var $extension Twig_ExtensionInterface */
			$this->extensions[$key] = $extension;
		}
		return $this;
	}

	/**
	 * Default implementation - auto detection by name. If you need to overwrite this, inherit this method.
	 */
	protected function getTemplateName()
	{
		$className = get_class($this);
		$templateName = '';
		$classParts = explode('\\', $className);
		if (($key = array_search('Controller', $classParts)) !== false) {
			unset($classParts[$key]);
			$templateName = strtolower(array_pop($classParts)) . '.html';
		}
		return $templateName;
	}

	/**
	 * @return Input
	 */
	protected function getInput()
	{
		return $this->input;
	}
	/**
	 * @return Context
	 */
	protected function getContext()
	{
		return $this->context;
	}

	/**
	 * @return array Exposed context 
	 */
	public function exposeContext()
	{
		$context = (array) $this->context->expose();
		$context['t'] = $this->loadTextlines();
		$elementContexts = array();
		$subElements = $this->subElements;
		foreach ($subElements as $name => $element) {
			/* @var $element Controller */
			$elementContext = (array) $element->context->expose();
			$elementContext['t'] = $element->loadTextlines();
			$elementContexts[$name] = $elementContext; 
		}
		$context['element'] = $elementContexts;
		return $context;
	}
	/**
	 * @return array(string $txlReference => string $txlLine)
	 */
	protected function loadTextlines()
	{
		$textlineStorage = new \FCore\Textline\Storage($this->getInput()->getLang());
		$textlineMap = $this->getTextlinesMap();
		return $textlineStorage->loadByMap($textlineMap);
	}

}
