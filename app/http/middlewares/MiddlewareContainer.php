<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.10.18
 * Time: 15:30
 */

namespace App\Http\Middlewares;


use Psr\Container\ContainerInterface;

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
        RequestParseMiddleware::class,
        RouteMiddleware::class,
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
    public function getMiddlewares(): ?array
    {
        $queue = $this->buildMiddlewares();

        return $queue;
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function buildMiddlewares(): array
    {
        $queue = [];

        foreach ($this->middlewares as $class) {
            try {
                $queue[] = $this->container->get($class);
            } catch (\Exception $e) {
                throw $e;
            }

        }

        return $queue;
    }


}