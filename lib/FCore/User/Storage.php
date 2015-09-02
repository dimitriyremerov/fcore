<?php
namespace FCore\User;
use FCore\Db\Storage as AbstractStorage;
use FCore\Db\Mongo\Storage as MongoStorage;
use FCore\User;

abstract class Storage extends MongoStorage
{
	const PASSWORD_SALT = 'T0JVjvLg74dJs';

	const FIELD_PROJECT_ID = 'projectId';
	const FIELD_EMAIL = 'email';
	const FIELD_OPENID_IDENTITY = 'openidIdentity';
	const FIELD_PASSWORD = 'password';
	const FIELD_STATUS = 'status';
	const FIELD_CREATED = 'created';
	const FIELD_UPDATED = 'updated';
	
	protected $collectionName = 'fcore.users';
	protected $primaryKey = 'user_id';
	
	public static function hashPassword($password)
	{
		return \md5($password . self::PASSWORD_SALT);
	}
	
	public function save(&$obj)
	{
		if (!$obj instanceof User) {
			throw new \InvalidArgumentException('User');
		}
		return parent::save($obj);
	}
	/**
	 * @param string $email
	 * @return User
	 */
	public function login($email, $password)
	{
		return $this->loadOne(array(
			self::FIELD_EMAIL => $email,
			self::FIELD_PASSWORD => self::hashPassword($password),
			self::FIELD_PROJECT_ID => $this->getProjectId(),
		));
	}
	
	abstract protected function getProjectId();
	
	
	public function loadOne(array $query)
	{
		$query += array(self::FIELD_PROJECT_ID => $this->getProjectId());
		return parent::loadOne($query);
	}
	
	public function load(array $query, $limit = null)
	{
		$query += array(self::FIELD_PROJECT_ID => $this->getProjectId());
		return parent::load($query, $limit);
	}

	/**
	 * @param int[] $ids
	 * @param bool $fillDeleted Whether to fill Deleted users as User_Deleted instead of just not returning them
	 * @return array(int $userId => User $user)
	 */
	public function loadList(array $ids, $fillDeleted = false)
	{
		$users = $this->loadByIds($ids);
		if ($fillDeleted) {
			foreach ($ids as $id) {
				if (!isset($users[$id])) {
					$users[$id] = $this->createUserDeleted();
				}
			}
		}
		return $users;
	}

	protected function createUserDeleted()
	{
		return new Deleted();
	}
}
