<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 21:40
 */

class BcryptFacadeTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testHash()
    {
        $hash = \Src\Facades\Bcrypt::hash('11111111');

        $this->assertTrue(is_string($hash));
        $this->assertTrue(strlen($hash) === 60);
    }

    /**
     *
     */
    public function testVerify()
    {
        $hash = \Src\Facades\Bcrypt::hash('11111111');

        $this->assertTrue(\Src\Facades\Bcrypt::verify('11111111', $hash));
        $this->assertFalse(\Src\Facades\Bcrypt::verify('22222222', $hash));
    }
}