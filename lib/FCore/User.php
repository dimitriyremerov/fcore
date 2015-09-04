<?php
namespace FCore;

use FCore\Entity;

abstract class User extends Entity
{
	/**
	 * @var int
	 */
	protected $projectId;
	/**
	 * @var string
	 */
	protected $email;
	/**
	 * @var string MD5 hash
	 */
	protected $password;
	/**
	 * @var int
	 */
	protected $status;
	/**
	 * @var int
	 */
	protected $created;
	/**
	 * @var int
	 */
	protected $updated;
	
	public function __construct($projectId, $email, $password, $status, $created, $updated)
	{
		$this->setProjectId($projectId);
		$this->setEmail($email);
		$this->setPassword($password);
		$this->setStatus($status);
		$this->setCreated($created);
		$this->setUpdated($updated);
	}

	/**
	 * @return the $projectId
	 */
	public function getProjectId()
	{
		return $this->projectId;
	}
	/**
	 * @return the $email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @return the $password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return the $status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return the $created
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @return the $updated
	 */
	public function getUpdated()
	{
		return $this->updated;
	}
	/**
	 * @param int $projectId
	 */
	public function setProjectId($projectId)
	{
		$this->projectId = $projectId;
	}
	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = (string) $email;
	}
	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = (string) $password;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status)
	{
		$this->status = (int) $status;
	}
	
	/**
	 * @param int $created
	 */
	public function setCreated($created)
	{
		$this->created = (int) $created;
	}

	/**
	 * @param int $updated
	 */
	public function setUpdated($updated)
	{
		$this->updated = (int) $updated;
	}
}
