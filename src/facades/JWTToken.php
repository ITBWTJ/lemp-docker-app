<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.11.18
 * Time: 23:18
 */

namespace Src\Facades;


use ITBWTJohnnyJWT\Helpers\AuthConfig;
use ITBWTJohnnyJWT\TokenBuilder;
use ITBWTJohnnyJWT\TokenVerify;

class JWTToken
{
    /**
     * @param array $user
     * @return string
     * @throws \ITBWTJohnnyJWT\Exceptions\NotSetConfigException
     * @throws \ITBWTJohnnyJWT\Exceptions\TokenException
     */
    static public function createToken(array $user): string
    {
        $config = new AuthConfig();
        $builder = new TokenBuilder();

        return $builder->setConfig($config)
            ->setData(['id' => $user['id'], 'email' => $user['email']])
            ->buildToken()
            ->getToken();

    }

    /**
     * @param string $token
     * @return bool
     */
    static public function verify(string $token): bool
    {
        $config = new AuthConfig();
        $verify = new TokenVerify();

        return $verify->setConfig($config)
            ->setToken($token)
            ->verify();
    }
}