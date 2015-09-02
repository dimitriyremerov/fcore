<?php

namespace FCore\Db\Storage;

class Helper
{
	const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
	const DATE_FORMAT = 'Y-m-d';
	
	/**
	 * @param int $time
	 * @return string Y-m-d H:i:s
	 */
	public static function timeToTimeStamp($time)
	{
		if ($time === null) {
			return null;
		}
		return date(self::TIMESTAMP_FORMAT, $time);
	}

	/**
	 * @param int $time
	 * @return string Y-m-d
	 */
	public static function timeToDate($time)
	{
		if ($time === null) {
			return null;
		}
		return date(self::DATE_FORMAT, $time);
	}
}
