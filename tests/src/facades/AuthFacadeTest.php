<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 22:42
 */

class AuthFacadeTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     */
    public function testIsNotAuth()
    {
        $this->assertFalse(\Src\Facades\Auth::isAuth());
    }

    /**
     * @throws AuthExceptions
     */
    public function testIsAuth()
    {
        \Src\Facades\Auth::setUser(['id' => 1]);

        $this->assertTrue(\Src\Facades\Auth::isAuth());

    }

    /**
     *
     */
    public function testLogut()
    {
        $this->assertTrue(\Src\Facades\Auth::isAuth());

        \Src\Facades\Auth::logout();

        $this->assertFalse(\Src\Facades\Auth::isAuth());
    }

    /**
     * @throws AuthExceptions
     */
    public function testGetUserId()
    {
        \Src\Facades\Auth::setUser(['id' => 33]);

        $this->assertTrue(\Src\Facades\Auth::getUserId() == 33);
    }

}