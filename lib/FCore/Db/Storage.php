<?php
namespace FCore\Db;

abstract class Storage
{
	abstract public function load(array $query, $limit = null);
	abstract public function save(&$obj);
}
