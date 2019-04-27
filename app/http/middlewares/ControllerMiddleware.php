<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.11.18
 * Time: 21:32
 */

namespace App\Http\Middlewares;

use Psr\Http\Message\{
    RequestInterface as Request, ResponseInterface as Response, ResponseInterface
};
use Relay\MiddlewareInterface;
use App\Exceptions\Controller\{
    ControllerIsNotExistsException, ControllerException, ControllerMethodIsNotExistsException
};
use Src\Http\Controller\ControllerCheck;
use Src\Http\Request\ParseHandler;

class ControllerMiddleware implements MiddlewareInterface
{
    use ParseHandler;
    use ControllerCheck;

    /**
     * @var
     */
    private $controller;

    /**
     * @var
     */
    private $method;

    /**
     * @var null|\Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * ControllerMiddleware constructor.
     */
    public function __construct()
    {
        $this->container = container();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable|null $next
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
       $this->parseHandler($request->getHandler());

       try {
           $response = $this->runController($request->getArgs());

           return $response;
       }catch (ControllerException $e) {
            return $response->withStatus(500)
                ->withStrToBody($e->getMessage());
       }
    }

    /**
     * @param array|null $args
     * @return ResponseInterface
     * @throws ControllerIsNotExistsException
     * @throws ControllerMethodIsNotExistsException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function runController(?array $args = []): ResponseInterface
    {
        if (!$this->container->has($this->controller)) {
            throw new ControllerIsNotExistsException($this->controller);
        }

        $this->controllerExists($this->controller);

        $this->controller = $this->container->get($this->controller);

        $this->methodExists($this->controller, $this->method);

        return call_user_func_array([$this->controller, $this->method], $args);
    }
}