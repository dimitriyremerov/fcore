<?php
namespace FCore;

class Lang
{
	const LANG_DEFAULT = 'en';
	
	protected static $validLangs = [
		'en',
	];
	
	public static function validateLang($lang)
	{
		if (in_array($lang, self::$validLangs)) {
			return true;
		}
		return false;
	}
}
