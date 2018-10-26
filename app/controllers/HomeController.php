<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.10.18
 * Time: 18:21
 */

namespace App\Controllers;
use App\Http\Request;
use App\Http\Response;

/**\
 * Class HomeController
 */
class HomeController extends BaseController
{


    /**
     *
     */
    public function index()
    {
        $body = 'Yes, it is home page';

        return $this->response
            ->withStatus(200)
            ->withBody($body)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withProtocolVersion($this->request->getProtocolVersion());
    }
}