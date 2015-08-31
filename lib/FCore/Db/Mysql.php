<?php
namespace FCore\Db;

class Mysql extends \FCore\Db
{
	/**
	 * @var Config
	 */
	protected $config;
	protected $link;
	
	private $res;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	public function __destruct()
	{
		\mysql_close($this->getLink());
	}

	private function getLink()
	{
		if (!isset($this->link)) {
			$this->link = \mysql_connect(
				$this->config->getHost(),
				$this->config->getLogin(),
				$this->config->getPassword()
			);
			\mysql_select_db($this->config->getDbName(), $this->link);
			\mysql_query('SET NAMES UTF8', $this->link);
		}
		return $this->link;
	}
	
	public function escapeString($string)
	{
		return \mysql_real_escape_string($string, $this->getLink());
	}
	
	public function query($sql)
	{
		$this->res = \mysql_query($sql, $this->getLink());
		if ($this->res === false) {
			throw new Exception(mysql_error($this->getLink()));
		}
		return $this->res;
	}
	public function getLastInsertId()
	{
		return \mysql_insert_id($this->getLink());
	}
	public function getAffectedRows()
	{
		return \mysql_affected_rows($this->getLink());
	}
}
