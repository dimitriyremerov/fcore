<?php

namespace FCore\Db\Mysql;
/**
 * Iterator over mysql resource query result
 */
class Iterator implements \Iterator, \Countable {
	/**
	 * @var resource
	 */
	private $res;
	/**
	 * @var int
	 */
	private $currentPos;
	/**
	 * @var array
	 */
	private $currentRow;
	/**
	 * @var int
	 */
	private $count;
	/**
	 * @param resource $res
	 * @param string $key_field
	 */
	public function __construct($res) {
		$this->res = $res;
		$this->count = mysql_num_rows($this->res);
		$this->rewind();
	}
	/** (non-PHPdoc)
	 * @see Iterator::current()
	 */
	public function current() {
		return $this->currentRow;
	}
	/** (non-PHPdoc)
	 * @see Iterator::next()
	 */
	public function next() {
		$this->currentRow = $this->fetchRow();
	}
	/** (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key() {
		return $this->currentPos;
	}
	/** (non-PHPdoc)
	 * @see Iterator::valid()
	 */
	public function valid() {
		return isset($this->currentRow);
	}
	/** (non-PHPdoc)
	 * @see Iterator::rewind()
	 */
	public function rewind() {
		if(!$this->count) {
			return;
		}
		mysql_data_seek($this->res, 0);
		$this->currentPos = -1;
		$this->currentRow = $this->fetchRow();
	}
	/** (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count() {
		return $this->count;
	}

	protected function fetchRow() {
		$row = mysql_fetch_assoc($this->res);
		if($row === false) {
			return null;
		}
		$this->currentPos++;
		return $row;
	}
}
