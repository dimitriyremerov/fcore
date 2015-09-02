<?php

namespace FCore\Db;

class Config
{
	private $host;
	private $login;
	private $password;
	private $dbName;
	
	public function __construct($host, $login, $password, $dbName)
	{
		$this->host = (string) $host;
		$this->login = (string) $login;
		$this->password = (string) $password;
		$this->dbName = (string) $dbName;
	}
	
	/**
	 * @return the $host
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @return the $login
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * @return the $password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return the $dbName
	 */
	public function getDbName()
	{
		return $this->dbName;
	}
}
