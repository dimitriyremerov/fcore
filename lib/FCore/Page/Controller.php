<?php
namespace FCore\Page;

abstract class Controller extends Element\Controller
{
	/**
	 * @var bool
	 */
	private $redirect = false;
	
	/**
	 * Default implementation - auto detection by name. If you need to overwrite this, inherit this method.
	 */
	protected function getTemplateName()
	{
		$className = get_class($this);
		$templateName = '';
		$classNameArray = explode('\\', $className);
		$templateName = null;
		if (array_pop($classNameArray) == 'Controller') {
			$templateName = strtolower(array_pop($classNameArray)) . '.html';
		}
		return $templateName;
	}

	protected function redirect($url)
	{
		header('Location: ' . $url);
		$this->redirect = true;
	}
}
