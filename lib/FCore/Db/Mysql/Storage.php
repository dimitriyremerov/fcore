<?php

namespace FCore\Db\Mysql;
use \FCore\Db;

abstract class Storage extends \FCore\Db\Storage
{
	protected $tableName = '';
	protected $primaryKey = '';

	protected $selectFields = array();
	protected $where = array();
	/**
	 * @var \FCore\Db\Mysql
	 */
	protected $db;
	private $limit = '';
	private $order = array();
	private $group = array();
	private $target;
	private $alias;
	private $aliasesInUse = array();
	private static $dbDefault;
	
	public function __construct(Db\Mysql $db = null)
	{
		if (!$this->tableName) {
			throw new \LogicException('Need to provide table name');
		}
		if (!$this->primaryKey) {
			throw new \LogicException('Need to provide primary key');
		}
		if (!$db) {
			if (isset(self::$dbDefault)) {
				$db = self::$dbDefault;
			} else {
				throw new \LogicException('Need to provide Db object');
			}
		}
		$this->alias = $this->createAlias($this->tableName);
		$this->target = sprintf('%s AS %s', $this->tableName, $this->alias);
		$this->db = $db;
	}

	public static function setDbDefault(Db\Mysql $db)
	{
		self::$dbDefault = $db;
	}
	
	/**
	 * @param array $fields
	 */
	protected function setSelectFields(array $fields)
	{
		foreach ($fields as &$field) {
			$field = sprintf('`%s`.`%s`', $this->alias, $field);
		}
		$this->selectFields = $fields;
		return $this;
	}
	
	private function getSelectFieldsSql()
	{
		if (!$this->selectFields) {
			$selectFieldsSql = '*';
		} else {
			$selectFieldsSql = join(',', $this->selectFields);
		}
		return $selectFieldsSql;
	}
	
	private function createAlias($tableName)
	{
		$alias = preg_replace('/(?<=^|_)(\w)[^_]+/', '$1', $tableName);
		$alias = str_replace('_', '', $alias);
		$i = 0;
		$aliasTest = $alias;
		while (true) {
			$aliasTest .= $i ?: '';  
			if (!isset($this->aliasesInUse[$aliasTest])) {
				break;
			}
			$i++;
		}
		$this->aliasesInUse[$aliasTest] = $aliasTest;
		return $aliasTest;
	}
	
	protected function reset()
	{
		$this->where = array();
		$this->selectFields = array();
		$this->limit = '';
		$this->order = array();
		$this->group = array();
		return $this;
	}
	/**
	 * @param array|string $where
	 */
	protected function where($where)
	{
		if (is_string($where)) {
			$this->where[] = $where;
		} else if (is_array($where)) {
			foreach ($where as $key => $val) {
				if (is_array($val)) {
					$this->where[] = sprintf("`%s`.`%s` IN ('%s')", $this->alias, $key, join("','", array_map(array($this->db, 'escapeString'), $val)));
				} else {
					$this->where[] = sprintf("`%s`.`%s`='%s'", $this->alias, $key, $this->db->escapeString($val));
				}
			}
		}
		return $this;
	}
	
	protected function order($order = null, $desc = false)
	{
		if (!isset($order)) {
			$this->order = array();
		} else {
			$orderSql = $order;
			if ($desc) {
				$orderSql .= ' DESC';
			}
			$this->order[] = $orderSql;
		}
		return $this;
	}

	protected function group($group = null)
	{
		if (!isset($group)) {
			$this->group = array();
		} else {
			$this->group[] = sprintf('`%s`.`%s`', $this->alias, $group);
		}
		return $this;
	}

	protected function limit($limit = null, $offset = null)
	{
		if (!isset($limit)) {
			$this->limit = '';
		} else {
			if (isset($offset)) {
				$this->limit = sprintf('LIMIT %u,%u', $offset, $limit);
			} else {
				$this->limit = sprintf('LIMIT %u', $limit);
			}
		}
		return $this;
	}

	protected function fetchById($id)
	{
		$rows = $this->fetchByIds(array($id));
		$row = null;
		if ($rows->count()) {
			$row = $rows->current();
		}
		return $row;
	}
	/**
	 * @param array $ids
	 * @return \FCore\Db\Mysql\Iterator
	 */
	protected function fetchByIds(array $ids)
	{
		return $this->where(array($this->primaryKey => $ids))->fetch();
	}
	/**
	 * @return \FCore\Db\Mysql\Iterator
	 */
	protected function fetch()
	{
		$sql = "SELECT %s FROM %s %s %s %s %s";
		$sql = sprintf($sql,
			$this->getSelectFieldsSql(),
			$this->target,
			$this->getWhereSql(),
			$this->getGroupSql(),
			$this->getOrderSql(),
			$this->limit
		);
		return $this->fetchQuery($sql);
	}
	
	private function getWhereSql()
	{
		if (!$this->where) {
			return '';
		} else {
			return sprintf('WHERE %s', join(' AND ', $this->where));
		}
	}
	
	private function getOrderSql()
	{
		if (!$this->order) {
			return '';
		} else {
			return sprintf('ORDER BY %s', join(', ', $this->order));
		}
	}

	private function getGroupSql()
	{
		if (!$this->group) {
			return '';
		} else {
			return sprintf('GROUP BY %s', join(', ', $this->group));
		}
	}
	/**
	 * @return \FCore\Db\Mysql\Iterator
	 */
	protected function fetchQuery($sql)
	{
		return new Iterator($this->query($sql));
	}

	protected function query($sql)
	{
		$this->reset();
		return $this->db->query($sql);
	}

	protected function join(Storage $storage, $on = null, $using = null)
	{
		if (isset($on)) {
			$condition = sprintf('ON %s', $on);
		} else if (isset($using)) {
			$condition = sprintf('USING (%s)', $using);
		} else {
			throw new \InvalidArgumentException('Either on or using must be provided!');
		}
		$alias = $this->createAlias($storage->tableName);
		$this->target .= sprintf(' INNER JOIN %s AS %s %s', $storage->tableName, $alias, $condition);
		$this->where = array_merge($this->where, $storage->where);
		$this->order = array_merge($this->order, $storage->order);
		$this->selectFields = array_merge($this->selectFields, $storage->selectFields);
		return $this;
	}
	
	protected function update(array $fields, $id = null)
	{
		$update = array();
		foreach ($fields as $field => $value) {
			$update[] = self::compileKeyValueStatement($field, $value);
		}
		if (isset($id)) {
			$this->reset()->where(array($this->primaryKey => $id));
		}

		$sql = "UPDATE %s SET %s %s %s %s";
		$sql = sprintf($sql,
			$this->target,
			join(', ', $update),
			$this->getWhereSql(),
			$this->getOrderSql(),
			$this->limit
		);

		$this->query($sql);
		return $this->db->getAffectedRows();
	}
	
	protected function insert(array $fields)
	{
		$insert = array();
		foreach ($fields as $field => $value) {
			$insert[] = self::compileKeyValueStatement($field, $value);
		}
		$sql = "INSERT INTO %s SET %s";
		$sql = sprintf($sql, $this->tableName, join(', ', $insert));
		$this->query($sql);
		return $this->db->getLastInsertId();
	}
	
	private static function compileKeyValueStatement($key, $value)
	{
		if ($value === null) {
			return sprintf('`%s`=NULL', $key);
		} else if (is_scalar($value)) {
			return sprintf("`%s`='%s'", $key, $value);
		} else {
			throw new \InvalidArgumentException('Invalid argument');
		}
	}
}
