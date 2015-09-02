<?php
namespace FCore;

class DomainObject
{
	protected $_id;
	
	const KEY_CLASS_NAME = '___CLASS_NAME';
    /**
     * @return array
     */
	public function expose($save = false)
	{
		$class = new \ReflectionClass($this);
		$property_names = self::getPropertyNames($class);
		$public = [];
		$links = [];
		foreach ($property_names as $name => $true) {
			if ($name == '_id' && $this->_id === null ) {
				continue;
			}
			$public[$name] = $this->exposeVal($this->$name, $save);
		}
		return $public;
	}
	public function set_id($_id)
	{
		$this->_id = $_id;
	}
	public function get_id()
	{
		return $this->_id;
	}
	private static function getPropertyNames(\ReflectionClass $class)
	{
		$properties = $class->getProperties(\ReflectionProperty::IS_PROTECTED);
		$propertyNames = array();
		foreach ($properties as $property) {
			$property instanceof \ReflectionProperty;
			$propertyNames[$property->getName()] = true;
		}
		$parent = $class->getParentClass();
		if ($parent) {
			$properties += self::getPropertyNames($parent);
		}
		return $propertyNames;
	}
	private static function exposeVal($val, $save = false)
	{
		if (is_array($val) || $val instanceof \Traversable) {
			$array = array();
			foreach ($val as $key => $array_val) {
				$array[$key] = self::exposeVal($array_val);
			}
			return $array;
// 		} else if (is_bool($val) || is_null($val)) {
// 			return (int)$val;
		} else if (is_scalar($val)) {
			return $val;
		} else if ($val instanceof \MongoId) {
			return $val;
		} else if ($val instanceof self) {
			if ($save) {
				$storage = \FCore\Db\Storage\Factory::createByClassName(get_class($val));
				$storage->save($val);
				return chr(0) . 's' . serialize(new \FCore\Db\Storage\Link(get_class($val), $val->get_id()));
			} else {
				return $val->expose();
			}
		}
		return null;
	}
}
