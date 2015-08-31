<?php

namespace FCore\Db;

class Helper
{
	/**
	 * @param array|Db_Iterator $iterator
	 */
	public static function exposeIterator($iterator)
	{
		if (is_array($iterator)) {
			return $iterator;
		}
		$array = array();
		foreach ($iterator as $key => $val) {
			$array[$key] = $val;
		}
		return $array;
	}
}
