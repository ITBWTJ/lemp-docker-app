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
use App\Http\Request;
use App\Http\Response;
use Psr\Http\Message\{RequestInterface, ResponseInterface};
use Relay\RelayBuilder;
use Src\Http\Controller\ControllerCheck;
use Src\Http\Middleware\MiddlewareMediator;
use Src\Http\Request\ParseHandler;

class Kernel
{
    use ParseHandler;
    use ControllerCheck;

    /**
     * @var
     */
    private $builder;

    /**
     * @var
     */
    private $handler;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var MiddlewareMediator
     */
    private $mediator;

    /**
     * @var
     */
    private $controller;

    /**
     * Kernel constructor.
     * @param RelayBuilder $builder
     * @param Handler $handler
     * @param MiddlewareMediator $mediator
     */
    public function __construct(RelayBuilder $builder, Handler $handler, MiddlewareMediator $mediator)
    {
        $this->builder = $builder;
        $this->handler =  $handler;
        $this->mediator = $mediator;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function run()
    {
        $this->runHandler();

        try {
            $group = $this->getMiddlewaresGroup();
            $middlewares = $this->getMiddlewares($group);

            $relay = $this->builder->newInstance($middlewares);
            $response = $relay($this->request, $this->response);
        } catch (\Exception $e) {

            $response = $this->response->withStatus(500)
                ->withStrToBody($e->getMessage());
        }


        $response->send();
        ob_end_flush();
    }

    /**
     *
     */
    private function runHandler(): void
    {
        $this->handler->handle();
        $this->response = $this->handler->getResponse();
        $this->request = $this->handler->getRequest();
    }

    /**
     * @param array|null $group
     * @return array
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getMiddlewares(?array $group = null): array
    {
        return $this->mediator->getMiddlewares($this->response->getStatusCode(), $group);
    }

    /**
     * @return array
     * @throws Exceptions\Controller\ControllerIsNotExistsException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getMiddlewaresGroup(): ?array
    {
        if ($this->response->getStatusCode() !== 200) return null;

        $this->parseHandler($this->request->getHandler());

        if ($this->controllerExists($this->controller)) {
            $controller = container()->get($this->controller);
        }

        return $controller->getMiddlewares();
    }





}