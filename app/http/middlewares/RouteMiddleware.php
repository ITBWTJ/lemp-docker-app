<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.18
 * Time: 14:59
 */

namespace App\Http\Middlewares;


use App\Http\Handler;
use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements \Relay\MiddlewareInterface
{
    /**
     * @var
     */
    private $container;

    /**
     * RouteMiddleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, ResponseInterface $response, callable $next = null)
    {
        $handler = new Handler($this->container, $this->container->get('router'), $request, $response);

        return $handler->handle();
    }
}