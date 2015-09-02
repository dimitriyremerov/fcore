<?php
namespace FCore;

class Autoloader
{
	private $packages = array();
	
	public function __construct()
	{
		$this->registerPackage(__NAMESPACE__, __DIR__);
	}
	
	public function registerPackage($namespace, $path)
	{
		$this->packages[$namespace] = $path;
	}
	
	public static function registerSpl(self $autoloader)
	{
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array($autoloader, 'autoload'));
	}

    public function autoload($className)
	{
		$packages = $this->packages;
		foreach ($packages as $namespace => $path) {
			if (strpos($className, $namespace) === 0) {
				$relativeClassName = substr($className, strlen($namespace));
				$fileName = $path . DIRECTORY_SEPARATOR . str_replace('\\', '/', $relativeClassName) . '.php'; 
				if (file_exists($fileName)) {
					include_once $fileName;
				}
			}
		}
	}
}
