<?php
namespace FCore;

class Auth extends \FCore\DomainObject
{
	/**
	 * @var string
	 */
	protected $userId;
	/**
	 * @var string
	 */
	protected $oauthProvider;
	/**
	 * @var string
	 */
	protected $oauthId;
	
	public function __construct($userId, $oauthProvider, $oauthId)
	{
		$this->setUserId($userId);
		$this->setOauthProvider($oauthProvider);
		$this->setOauthId($oauthId);
	}

	public function getUserId()
	{
		return $this->userId;
	}
	public function getOauthProvider()
	{
		return $this->oauthProvider;
	}
	public function getOauthId()
	{
		return $this->oauthId;
	}
	public function setUserId($userId)
	{
		$this->userId = (string) $userId;
	}
	public function setOauthProvider($oauthProvider)
	{
		$this->oauthProvider = (string) $oauthProvider;
	}
	public function setOauthId($oauthId)
	{
		$this->oauthId = (string) $oauthId;
	}
	
}
