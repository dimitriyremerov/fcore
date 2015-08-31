<?php
namespace FCore\User;
use \FCore\User;

class Deleted extends User
{
	const STATUS_DELETED = 99;
	
	public function __construct()
	{
		parent::__construct(
			0,
			'DELETED',
			'',
			self::STATUS_DELETED,
			0,
			0
		);
	}
	
}
