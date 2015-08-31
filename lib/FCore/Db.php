<?php
namespace FCore;

abstract class Db
{
	abstract public function escapeString($string);
	
	abstract public function query($sql);
	abstract public function getLastInsertId();
	abstract public function getAffectedRows();
}
