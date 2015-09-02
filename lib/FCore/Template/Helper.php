<?php
namespace FCore\Template;

class Helper
{
	/**
	 * @param string $templateName
	 * @return string
	 */
	public static function sanitizeTemplateName($templateName)
	{
		return str_replace(\DIRECTORY_SEPARATOR, '', (string) $templateName);
	}
}
