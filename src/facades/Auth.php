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
    static public function setUser(array $user): void
    {
        if (empty($user['id'])) {
            throw new AuthExceptions('User has not id');
        }

        self::$user = $user;
    }

    /**
     * @return int|null
     */
    static public function getUserId(): ?int
    {
        if (!empty(self::$user)) {
            return self::$user['id'];
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