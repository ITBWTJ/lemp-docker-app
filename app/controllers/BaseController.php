<?php

namespace App\Controllers;


use App\Http\Request;
use App\Http\Response;

class BaseController
{
    /**
     * @var
     */
    protected $request;

    /**
     * @var
     */
    protected $response;

    /**
     * HomeController constructor.
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}