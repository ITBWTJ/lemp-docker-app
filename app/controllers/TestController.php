<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19.11.18
 * Time: 21:08
 */

namespace App\Controllers;

/**
 * Class TestController
 * @package App\Controllers
 */
class TestController extends BaseController
{
    /**
     * @return $this|\App\Http\Response
     */
    public function test()
    {
        return $this->response->withStrToBody('Test method');
    }
}