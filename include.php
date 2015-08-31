<?php
if (!defined('CORE_ROOT')) {
	define('CORE_ROOT', __DIR__);
}
define('CORE_LIB', CORE_ROOT . '/lib');

require_once CORE_LIB . '/FCore/Autoloader.php';
require_once CORE_LIB . '/SensioLabs/Twig/Autoloader.php';
require_once CORE_LIB . '/FirePHPCore/fb.php';
\Twig_Autoloader::register();

