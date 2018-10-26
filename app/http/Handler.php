<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.10.18
 * Time: 1:35
 */

namespace App\Http;


use FastRoute\Dispatcher\GroupCountBased;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * Handler constructor.
     */
    public function __construct(GroupCountBased $router, Request $request, Response $response)
    {
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;

        $this->formattingData();
    }

    /**
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function handle(): ResponseInterface
    {
        switch ($this->status) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $this->response = $this->handlerNotFound();
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->response = $this->handlerNotAllowed();
                // ... 405 Method Not Allowed
                break;
            case \FastRoute\Dispatcher::FOUND:
                $this->response = $this->handlerFound();
                // ... call $handler with $vars
                break;
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
     * @throws \ReflectionException
     */
    private function handlerFound(): ResponseInterface
    {
        $explode = explode('@', $this->handler);
        $method = new \ReflectionMethod($explode[0], $explode[1]);

        return $method->invoke(new $explode[0], $this->vars);

    }

    /**
     *
     */
    private function handlerNotAllowed(): ResponseInterface
    {
        return $this->response->withStatus($this->status);
    }

    /**
     *
     */
    private function handlerNotFound(): ResponseInterface
    {
        return $this->response->withStatus($this->status);
    }
}