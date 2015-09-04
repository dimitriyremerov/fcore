<?php
namespace FCore\DomainObject;
use FCore\Entity;

class Helper
{
	public static function createObject(array $data, array &$links)
	{
		$className = $data[Entity::KEY_CLASS_NAME];
		$reflection = new \ReflectionClass($className);
		$instance = $reflection->newInstanceWithoutConstructor();
		$properties = $reflection->getProperties();

		foreach ($properties as $property) {
			/* @var $property \ReflectionProperty */
			$name = $property->getName();
			if (array_key_exists($name, $data)) {
				$rawData = $data[$name];
				$property->setAccessible(true);
				$property->setValue($instance, self::createValue($rawData, $links));
			}
		}
		return $instance;
	}
	
	private static function createValue($rawData, array &$links)
	{
		if (is_array($rawData)) {
			$return = []; 
			foreach ($rawData as $key => $val) {
				$return[$key] = self::createValue($val, $links);
			}
			return $return;
		} else if ($object = self::detectSerialized($rawData)) {
			if ($object instanceof \FCore\Db\Storage\Link) {
				$links[$object->getClassName()][] = $object->getObjectId();
			}
			return $object;
		}
		return $rawData;
	}

	public static function fillLinks($object, array $linksLoaded)
	{
		$reflection = new \ReflectionClass($object);
		$properties = $reflection->getProperties();
		
		foreach ($properties as $property) {
			$property->setAccessible(true);
			$value = $property->getValue($object);
			if ($value instanceof \FCore\Db\Storage\Link) {
				if (isset($linksLoaded[$value->getClassName()][$value->getObjectId()])) {
					$property->setValue($object, $linksLoaded[$value->getClassName()][$value->getObjectId()]);
				}
			}
		} 
	}
	
	private static function detectSerialized($value)
	{
		if (is_string($value) && substr($value, 0, 2) == chr(0) . 's') {
			$serialized = substr($value, 2);
			$object = unserialize($serialized);
			return $object;
		}
		return null;
	}
}
