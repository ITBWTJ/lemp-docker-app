<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 21:45
 */

class JWTTokenFacadeTest extends \PHPUnit\Framework\TestCase
{
    public function testToken()
    {
        $user = [
            'id' => 2,
            'email' => 3242,
        ];

        $token = \Src\Facades\JWTToken::createToken($user);

        $this->assertTrue(is_string($token));
    }

    public function testVerify()
    {
        $user = [
            'id' => 2,
            'email' => 3242,
        ];

        $token = \Src\Facades\JWTToken::createToken($user);

        $this->assertTrue(\Src\Facades\JWTToken::verify($token));
    }

    /**
     * @throws \ITBWTJohnnyJWT\Exceptions\NotSetConfigException
     * @throws \ITBWTJohnnyJWT\Exceptions\TokenException
     */
    public function testVerifyFailed()
    {
        $user = [
            'id' => 2,
            'email' => 3242,
        ];

        $token = \Src\Facades\JWTToken::createToken($user);

        $this->assertFalse(\Src\Facades\JWTToken::verify($token . '42'));
    }


}