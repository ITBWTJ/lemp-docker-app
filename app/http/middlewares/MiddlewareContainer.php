<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.18
 * Time: 15:30
 */

namespace App\Http\Middlewares;

use Psr\Container\ContainerInterface;

/**
 * Class MiddlewareContainer
 * @package App\Http\Middlewares
 */
class MiddlewareContainer
{
    /**
     * @var
     */
    private $container;

    /**
     * @var
     */
    private $middlewares = [
        'start' => [
            RequestParseMiddleware::class,
        ],
        'end' => [
            ControllerMiddleware::class,
        ],
        'web' => [
//            RouteMiddleware::class,
        ],
        'api' => [
            AuthMiddleware::class,
        ],
        'error' => [

        ]

    ];


    /**
     * MiddlewareContainer constructor.
     */
    public function __construct()
    {
        $this->container = container();
    }

    /**
     * @return array|null
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getStart(): ?array
    {
        $queue = $this->buildMiddlewares('start');

        return $queue;
    }

    /**
     * @return array|null
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getEnd(): ?array
    {
        $queue = $this->buildMiddlewares('end');

        return $queue;
    }

    /**
     * @return array|null
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getByGroup($key): ?array
    {
        $queue = $this->buildMiddlewares($key);

        return $queue;
    }

    /**
     * @return array|null
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getError(): ?array
    {
        $queue = $this->buildMiddlewares('error');

        return $queue;
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function buildMiddlewares($key): array
    {
        $queue = [];

        foreach ($this->getByKey($key) as $class) {
            try {
                $queue[] = $this->container->get($class);
            } catch (\Exception $e) {
                throw $e;
            }

        }

        return $queue;
    }

    /**
     * @param $key
     * @return array|mixed
     */
    private function getByKey($key)
    {
        if (!empty($this->middlewares[$key])) {
            return $this->middlewares[$key];
        }

        return [];
    }


}