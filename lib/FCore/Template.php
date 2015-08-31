<?php

namespace FCore;
use FCore\Template\Exception;
/**
 * 
 * Very simple templater (temporary)
 * @author Dimitriy
 * @todo extend later 
 *
 */
class Template
{
	/**
	 * @var string
	 */
	protected $templatePath = TPL_PATH; 
	/**
	 * @var string
	 */
	protected $templateCachePath = TPL_CACHE_PATH;
	/**
	 * @var string
	 */
	protected $template;
	/**
	 * @var bool
	 */
	protected $useCache = false;

	/**
	 * @param string $template
	 * @param string[optional] $templatePath
	 */
	public function __construct($template, $templatePath = null)
	{
		$this->template = Template\Helper::sanitizeTemplateName($template);
		if (isset($templatePath)) {
			$this->templatePath = (string) $templatePath;
		}
	}
	/**
	 * @param bool $useCache
	 */
	public function setUseCache($useCache)
	{
		$this->useCache = (bool) $useCache;
	}

	public function compile(stdClass $context)
	{
		ob_start();
		try {
			include $this->loadTemplate();
		} catch (Exception $exc) {
			echo 'Error: ' . $exc->getMessage();
		}
		return ob_get_clean();
	}

	/**
	 * @return string Path to the compiled template file or NULL if not found
	 */
	protected function loadTemplate()
	{
		if ($this->useCache && null !== ($templateCached = $this->loadTemplateCached())) {
			return $templateCached;
		} else {
			$fileName = $this->templatePath . $this->template;
			if (file_exists($fileName)) {
				$data = file_get_contents($fileName);
				$data = preg_replace('/\{=\s*(.*)\s*\}/U', '<?php echo $context->t[\'$1\'];?>', $data);
				$data = preg_replace('/\{\{\s*(.*)\s*\}\}/U', '<?php echo $context->$1; ?>', $data);
				$data = preg_replace_callback('/\{%\s*([^\}]*)\s*%\}/U', function ($matches) {
					$inner = str_replace('$', '$context->', $matches[1]);
					return sprintf('<?php %s ?>', $inner);
				}, $data);
				return $this->saveTemplateCached($data);
			} else {
				throw new Exception(Template\Exception::ERR_FILE_NOT_EXISTS);
			}
		}
	}

	/**
	 * @return string Path to the compiled template file or NULL if not found
	 */
	protected function loadTemplateCached()
	{
		$fileName = $this->getTemplateNameCached();
		if (file_exists($fileName)) {
			return $fileName;
		} else {
			return null;
		}
	}
	/**
	 * @param string $data
	 * @return string Path to the compiled template file
	 * @throws Template_Exception In case of write failure
	 */
	protected function saveTemplateCached($data)
	{
		$fileName = $this->getTemplateNameCached();
		if (file_put_contents($fileName, $data) === false) {
			throw new Exception(Template\Exception::ERR_FILE_CANT_BE_WRITTEN);
		} else {
			return $fileName;
		}
	}
	
	private function getTemplateNameCached()
	{
		return $this->templateCachePath . $this->template . '.php';
	}
}
