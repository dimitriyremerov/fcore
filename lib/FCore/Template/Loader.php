<?php
namespace FCore\Template;

class Loader
{
	/**
	 * @var \Twig_Environment 
	 */
	private $env;
	
	public function __construct(array $extensions = null)
	{
		if (!isset($extensions)) {
			$extensions = array();
		}
		$this->env = self::createEnvironement();
		$this->addExtensions($extensions);
	}
	
	public function addExtensions(array $extensions)
	{
		foreach ($extensions as $extension) {
			/* @var $extension \Twig\ExtensionInterface */
			$this->env->addExtension($extension);
		}
	}

	/**
	 * @return \Twig_Template
	 */
	public function loadTemplate($templateName)
	{
		return $this->env->loadTemplate($templateName);
	}	
	/**
	 * @return \Twig_Environment
	 */
	private static function createEnvironement()
	{
		$loader = new \Twig_Loader_Filesystem( \TPL_PATH_TWIG );
		$env = new \Twig_Environment($loader, [
			'debug' => \CONF_TWIG_DEBUG,
			'autoescape' => false,
			'cache' => \CONF_TWIG_DEBUG ? false : \TPL_CACHE_PATH,
			'auto_reload' => false,
			'trim_blocks' => true,
		]);
		return $env;
	}
	
	public static function getTemplateFileName($templateName)
	{
		return \TPL_PATH_TWIG . '/' . $templateName;
	}
}
