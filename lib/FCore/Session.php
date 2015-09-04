<?php
/**
 * Created by PhpStorm.
 * User: Dimitriy
 * Date: 04/09/15
 * Time: 17:20
 */

namespace FCore;


class Session
{

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Session destructor.
     */
    public function __destruct()
    {
        session_write_close();
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key) : string
    {
        return $_SESSION[$key] ?? null;
    }
}