<?php
namespace FCore;

class Auth extends Entity
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
	
	public function __construct(string $userId, string $oauthProvider, string $oauthId)
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
	public function setUserId(string $userId)
	{
		$this->userId = $userId;
	}
	public function setOauthProvider(string $oauthProvider)
	{
		$this->oauthProvider = $oauthProvider;
	}
	public function setOauthId(string $oauthId)
	{
		$this->oauthId = $oauthId;
	}
	
}
