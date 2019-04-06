<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.18
 * Time: 23:55
 */

namespace App\Http\middlewares;


use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Relay\MiddlewareInterface;

class ApiMiddleware2 implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if (!$request->hasHeader('test-header')) {
            return $response->withHeader('header2', 'value');
        }


        return $next($request, $response);
    }
}