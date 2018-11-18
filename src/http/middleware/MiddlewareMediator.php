<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.11.18
 * Time: 22:58
 */

namespace Src\Http\Middleware;

use App\Http\Middlewares\MiddlewareContainer;

class MiddlewareMediator
{
    /**
     * @var MiddlewareContainer
     */
    private $container;

    /**
     * @var MiddlewareContainer
     */
    private $middlewares;

    /**
     * MiddlewareMediator constructor.
     * @param MiddlewareContainer $container
     */
    public function __construct(MiddlewareContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @param $status
     * @param null $group
     * @return MiddlewareContainer|array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getMiddlewares($status, $group = null)
    {
        switch ($status) {
            case 200:
                $this->middlewares = $this->getBaseMiddlewares($group);
                break;
            case 404:
                $this->middlewares = $this->getErrorMiddlewares();
                break;
            case 405:
                $this->middlewares = $this->getErrorMiddlewares();
                break;

            default:
                $this->middlewares = $this->getErrorMiddlewares();
        }

        return $this->middlewares;
    }

    /**
     * @param null $group
     * @return array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getBaseMiddlewares($group = null)
    {
        $middlewares =   $this->container->getStart();

        if (!empty($group)) {
            if (is_array($group)) {
                foreach ($group as $item) {
                    $middlewares = array_merge($middlewares, $this->container->getByGroup($item));
                }
            } else {
                $middlewares = array_merge($middlewares, $this->container->getByGroup($group));
            }
        }


        return array_merge($middlewares, $this->container->getEnd());

    }

    /**
     * @return array|null
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getErrorMiddlewares()
    {
        return $this->container->getError();
    }



}