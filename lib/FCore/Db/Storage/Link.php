<?php
namespace FCore\Db\Storage;

class Link
{
	/**
	 * @var string
	 */
	protected $className;
	/**
	 * @var string
	 */
	protected $objectId;

	public function __construct($className, $objectId)
	{
		$this->setClassName($className);
		$this->setObjectId($objectId);
	}
	/**
	 * @return the $className
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 * @return the $objectId
	 */
	public function getObjectId()
	{
		return $this->objectId;
	}

	/**
	 * @param string $className
	 */
	public function setClassName($tableName)
	{
		$this->className = (string) $tableName;
	}

	/**
	 * @param string $objectId
	 */
	public function setObjectId($objectId)
	{
		$this->objectId = (string) $objectId;
	}
}
