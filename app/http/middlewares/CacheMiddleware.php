<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.11.18
 * Time: 15:21
 */

namespace App\Http\Middlewares;


use Predis\Client;
use Predis\PredisException;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Relay\MiddlewareInterface;

class CacheMiddleware implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $container = container();

        try {
            $redis = new Client([
                'scheme' => 'tcp',
                'host' => 'redis',
                'port' => '6379',
            ]);

            $container->set('redis', $redis);
        } catch (PredisException $e) {
            dd($e->getMessage());
        }

        return $next($request, $response);
    }
}