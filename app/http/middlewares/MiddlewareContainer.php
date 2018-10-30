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
        RouteMiddleware::class,
    ];


    /**
     * MiddlewareContainer constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array|null
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

            }

        }

        return $queue;
    }


}