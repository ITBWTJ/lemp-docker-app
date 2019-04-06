<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 22:29
 */

namespace Src\Facades;


use Src\Exceptions\AuthExceptions;

class Auth
{
    static private $user;

    /**
     * @param array $user
     * @throws AuthExceptions
     */
    static public function setUser( $user): void
    {
        self::$user = $user;
    }

    /**
     * @return mixed
     */
    static public function getUser()
    {
        if (!empty(self::$user)) {
            return self::$user;
        }

        return null;
    }

    /**
     * @return bool
     */
    static public function isAuth(): bool
    {
        return !is_null(self::$user);
    }

    /**
     *
     */
    static public function logout(): void
    {
        self::$user = null;
    }
}