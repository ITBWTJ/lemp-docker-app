<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 12:31
 */

namespace App;

use App\Http\Handler;
use App\Http\Middlewares\MiddlewareContainer;
use Psr\Http\Message\{RequestInterface, ResponseInterface};
use Relay\RelayBuilder;

class Kernel
{
    /**
     * @var
     */
    private $builder;

    /**
     * @var
     */
    private $middlewareContainer;

    /**
     * @var
     */
    private $request;

    /**
     * @var
     */
    private $response;

    /**
     * Kernel constructor.
     * @param RelayBuilder $builder
     * @param MiddlewareContainer $middlewareContainer
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(RelayBuilder $builder, MiddlewareContainer $middlewareContainer, RequestInterface $request, ResponseInterface $response)
    {
        $this->builder = $builder;
        $this->middlewareContainer =  $middlewareContainer;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     *
     */
    public function run()
    {
        $relay = $this->builder->newInstance($this->middlewareContainer->getMiddlewares());
        $response = $relay($this->request, $this->response);
        $response->send();
        ob_end_flush();
    }




}