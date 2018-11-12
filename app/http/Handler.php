<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.10.18
 * Time: 1:35
 */

namespace App\Http;


use FastRoute\Dispatcher\GroupCountBased;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class Handler
{

    /**
     * @var
     */
    private $status;

    /**
     * @var
     */
    private $handler;

    /**
     * @var
     */
    private $vars;


    /**
     * @var \FastRoute\Dispatcher\GroupCountBased
     */
    private $router;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var
     */
    private $response;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Handler constructor.
     * @param ContainerInterface $container
     * @param GroupCountBased $router
     * @param Request $request
     * @param Response $response
     */
    public function __construct(GroupCountBased $router, Request $request, Response $response)
    {
        $this->container = container();
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;

        $this->formattingData();
    }

    /**
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle(): ResponseInterface
    {
        try {
            switch ($this->status) {
                case \FastRoute\Dispatcher::NOT_FOUND:
                    $this->response = $this->handlerNotFound();
                    break;
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                    $this->response = $this->handlerNotAllowed();
                    break;
                case \FastRoute\Dispatcher::FOUND:
                    $this->response = $this->handlerFound();
                    break;
            }
        } catch (\Exception $e) {
            $this->response = $this->handlerServerError($e);
        }

        return $this->response;
    }


    /**
     * @return array
     */
    private function buildRouteInfo(): array
    {
        $method = $this->getMethod();
        $uri = $this->getUri();

        return $this->router->dispatch($method, $uri);
    }

    /**
     *
     */
    private function formattingData(): void
    {
        $routeInfo = $this->buildRouteInfo();

        $this->status = $routeInfo[0];
        $this->handler = $routeInfo[1];
        $this->vars = $routeInfo[2];
    }

    /**
     * @return string
     */
    private function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * @return \GuzzleHttp\Psr7\Uri|\Psr\Http\Message\UriInterface|string
     */
    private function getUri(): string
    {
        return $this->request->getUri();
    }

    /**
     * @return ResponseInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    private function handlerFound(): ResponseInterface
    {

        $explode = explode('@', $this->handler);
        $controller = $this->container->get($explode[0]);
        $method = new \ReflectionMethod($explode[0], $explode[1]);

        return $method->invoke($controller, $this->vars);

    }

    /**
     *
     */
    private function handlerNotAllowed(): ResponseInterface
    {
        return $this->response
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withStatus(405);
    }

    /**
     * @return ResponseInterface
     */
    private function handlerNotFound(): ResponseInterface
    {
        return $this->response
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withStatus(404);
    }

    /**
     * @return ResponseInterface
     */
    private function handlerServerError(\Exception $e): ResponseInterface
    {
        return $this->response
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withBody(stream_for($e->getMessage()))
            ->withStatus(500);
    }
}