<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.18
 * Time: 23:44
 */

namespace App\Controllers\Api;


class UserController extends ApiBaseController
{
    /**
     * @return \App\Http\Response
     */
    public function index()
    {
        return $this->json(['data' => [0 => ['id' => 1, 'name' => 'Ivan']]]);
    }
}