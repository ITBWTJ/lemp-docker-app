<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 12:31
 */

namespace App;

class Kernel
{
    private $router;

    /**
     * Kernel constructor.
     * @param \FastRoute\Dispatcher\GroupCountBased $router
     */
    public function __construct(\FastRoute\Dispatcher\GroupCountBased $router)
    {
        $this->router = $router;
    }

    /**
     * @throws \ReflectionException
     */
    public function run()
    {

// Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->router->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $explode = explode('@', $handler);
                $method = new \ReflectionMethod($explode[0], $explode[1]);
                echo $method->invoke(new $explode[0], $vars);

                // ... call $handler with $vars
                break;
        }
    }
}