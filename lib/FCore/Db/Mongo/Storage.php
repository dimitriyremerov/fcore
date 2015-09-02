<?php
namespace FCore\Db\Mongo;
use FCore\DomainObject;

abstract class Storage extends \FCore\Db\Storage
{
	/**
	 * @var \Mongo
	 */
	protected $mongo;
	/**
	 * @var \MongoCollection
	 */
	private $collection;
	/**
	 * @var string
	 */
	protected $dbName;
	/**
	 * @var string
	 */
	protected $collectionName;

	public function __construct(\Mongo $mongo = null)
	{
		if (!isset($mongo)) {
			$mongo = new \Mongo();
		}
		$this->mongo = $mongo;
		$this->detectCollectionName();
	}

	/**
	 * @return \MongoCollection
	 */
	private function getCollection()
	{
		if (!isset($this->collection)) {
			$this->collection = $this->mongo->selectDB($this->dbName)->selectCollection($this->collectionName);
		}
		return $this->collection;
	}
	
	protected function detectCollectionName()
	{
		$storageName = static::class;
		$classParts = explode('\\', $storageName);
		$projectName = array_shift($classParts);
		$this->dbName = strtolower($projectName);
		if (($key = array_search('Storage', $classParts)) !== false) {
			unset($classParts[$key]);
		}
		$this->collectionName = strtolower(implode('_', $classParts));
	}

	/**
	 * @param array|DomainObject $obj
	 */
	public function save(&$obj)
	{
		$links = [];
		if ($obj instanceof DomainObject) {
			$arr = $obj->expose(true);
		} else {
			$arr = $obj;
		}
		$this->getCollection()->save($arr);
		if ($obj instanceof DomainObject) {
			$obj->set_id($arr['_id']);
		} else {
			$obj['_id'] = $arr['_id'];
		}
	}

	public function loadById($id, $mongoId = true)
	{
		$id = $mongoId ? new \MongoId($id) : $id;
		return self::loadOne(['_id' => $id]);
	}
	
	public function loadByIds(array $ids)
	{
		foreach ($ids as $key => $id) {
			$ids[$key] = new \MongoId($id);
		}
		return self::load(['_id' => ['$in' => $ids]]);
	}
	
	public function loadOne(array $query)
	{
		$arr = self::load($query, 1);
		if ($arr) {
			reset($arr);
			return current($arr);
		}
		return null;
	}
	
	public function load(array $query, $limit = null)
	{
		$cursor = $this->getCollection()->find($query);
		if ($limit) {
			/* @var $cursor \MongoCursor */
			$cursor = $cursor->limit($limit);
		}
		$return = [];
		$links = [];

		$className = $this->getClassName();
		foreach ($cursor as $obj) {
			$id = (string) $obj['_id'];
			if ($className) {
				$obj[DomainObject::KEY_CLASS_NAME] = $className;
				$obj = DomainObject\Helper::createObject($obj, $links);
			}
			$return[$id] = $obj;
		}
		$linksLoaded = [];
		foreach ($links as $className => $objectIds) {
			//TODO Implement needs
			$storage = \FCore\Db\Storage\Factory::createByClassName($className);
			foreach ($objectIds as $key => $id) {
				$objectIds[$key] = new \MongoId($id);
			}
			$linksLoaded[$className] = $storage->load(['_id' => ['$in' => $objectIds]]);
		}
		foreach ($return as $object) {
			if (is_object($object)) {
				DomainObject\Helper::fillLinks($object, $linksLoaded);
			}
		}
		return $return;
	}
	
	public function delete(array $query)
	{
		return $this->getCollection()->remove($query);
	}

	protected function getClassName()
	{
		$storageName = get_class($this);
		$classParts = explode('\\', $storageName);
		if (($key = array_search('Storage', $classParts)) !== false) {
			unset($classParts[$key]);
			$className = implode('\\', $classParts); 
			if (class_exists($className)) {
				return $className;
			} else {
				array_shift($classParts);
				array_unshift($classParts, 'FCore');
				$className = implode('\\', $classParts);
				if (class_exists($className)) {
					return $className;
				}
			}
		}
		return null;
	}
}
