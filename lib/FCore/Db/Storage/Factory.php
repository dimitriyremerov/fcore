<?php
namespace FCore\Db\Storage;

class Factory
{
	/**
	 * @return \FCore\Db\Storage
	 */
	public static function createByClassName($className)
	{
		$storageClassName = $className . '\\Storage';
		return new $storageClassName();
	}
}
