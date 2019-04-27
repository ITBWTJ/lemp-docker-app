<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.11.18
 * Time: 22:54
 */

namespace Src\Facades;

class Bcrypt
{
    /**
     *
     */
    private const DEFAULT_ALGO = PASSWORD_BCRYPT;

    /**
     *
     */
    private const DEFAULT_OPTION_COST = 8;

    /**
     * @param string $string
     * @return bool|string
     */
    static public function hash(string $string)
    {
        return password_hash($string, self::DEFAULT_ALGO, ['cost' => self::DEFAULT_OPTION_COST]);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    static public function verify(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }
}