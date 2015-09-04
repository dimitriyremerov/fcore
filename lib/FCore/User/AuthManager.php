<?php
/**
 * Created by PhpStorm.
 * User: Dimitriy
 * Date: 04/09/15
 * Time: 18:15
 */

namespace FCore\User;

use FCore\Session;

class AuthManager
{
    protected $session;
    protected $userStorage;

    /**
     *
     */
    public function __construct(Session $session, Storage $userStorage)
    {
        $this->session = $session;
        $this->userStorage = $userStorage;
    }

    /**
     * @return \FCore\User|null
     */
    public function authUser()
    {
        $user = null;
        if ($userId = $this->session->get('userId')) {
            $user = $this->userStorage->loadById($userId);
        }
        return $user;
    }
}