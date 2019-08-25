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
use Spiral\Debug\Dumper;
use Src\Http\Request\RequestParseFactory;
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

	private $requestParseFactory;


	/**
	 * Handler constructor.
	 * @param GroupCountBased $router
	 * @param RequestInterface $request
	 * @param Response $response
	 * @param RequestParseFactory $requestParseFactory
	 */
	public function __construct(
		GroupCountBased $router,
		RequestInterface $request,
		Response $response,
		RequestParseFactory $requestParseFactory
	)
	{
		$this->requestParseFactory = $requestParseFactory;
		$this->container = container();
		$this->router = $router;
		$this->request = $request;
		$this->response = $response;
	}


	/**
	 * @return Request
	 */
	public function getRequest(): RequestInterface
	{
		return $this->request;
	}

	/**
	 * @param Request $request
	 */
	public function setRequest(RequestInterface $request): void
	{
		$this->request = $request;
	}

	/**
	 * @return mixed
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * @param mixed $response
	 */
	public function setResponse($response): void
	{
		$this->response = $response;
	}

	/**
	 * @return string
	 */
	public function getHandler(): string
	{
		return $this->handler;
	}

	/**
	 *
	 */
	public function handle(): void
	{
		$this->request = container()->get('request');
		$this->formattingData();
		$this->replaceRequest();

		$this->request->setHandler($this->handler, $this->vars);
		$this->container->set('request', $this->request);

		try {
			switch ($this->status) {
				case \FastRoute\Dispatcher::NOT_FOUND:
					$this->handlerNotFound();
					break;
				case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
					$this->handlerNotAllowed();
					break;
				case \FastRoute\Dispatcher::FOUND:
					$this->handlerFound();
					break;
			}
		} catch (\Exception $e) {
			$this->handlerServerError($e);
		}


	}


	/**
	 * @return array
	 */
	private function buildRouteInfo(): array
	{
		$method = $this->getMethod();
		$uri = $this->getUri();
		$uri = $this->clearUrl($uri);
		$dumper = new Dumper();
		$dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());
		$dumper->dump($uri, Dumper::ERROR_LOG);
		return $this->router->dispatch($method, $uri);
	}

	private function clearUrl(string $url): string
	{
		$position = strpos($url, '?');

		return $position ? substr($url, 0, $position) : $url;

	}

	/**
	 *
	 */
	private function formattingData(): void
	{
		$routeInfo = $this->buildRouteInfo();

		$dumper = new Dumper();
		$dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());
		$dumper->dump($routeInfo, Dumper::ERROR_LOG);

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
		return $this->request->getUri()->getPath();
	}

	/**
	 *
	 */
	private function handlerFound(): void
	{
		$this->response = $this->response->withStatus(200);
//        $this->request->set('handler', $this->handler);
//        $this->request->set('args', $this->vars);
	}

	/**
	 *
	 */
	private function handlerNotAllowed(): void
	{
		$this->response = $this->response
			->withHeader('Content-Type', 'text/html; charset=UTF-8')
			->withStatus(405);
	}

	/**
	 *
	 */
	private function handlerNotFound(): void
	{
		if (!$this->isApiRoute()) {
			$this->setDefaultApiRoute();

			$this->response = $this->response->withStatus(200);

//            $this->request->set('handler', $this->handler);
//            $this->request->set('args', $this->vars);
		} else {
			$this->response = $this->response
				->withHeader('Content-Type', 'text/html; charset=UTF-8')
				->withStrToBody('Page not found;(')
				->withStatus(404);
		}


	}

	/**
	 * @param \Exception $e
	 */
	private function handlerServerError(\Exception $e): void
	{
		$this->response = $this->response
			->withHeader('Content-Type', 'text/html; charset=UTF-8')
			->withBody(stream_for($e->getMessage()))
			->withStatus(500);
	}

	private function setDefaultApiRoute(): void
	{
		$routeInfo = $this->router->dispatch('GET', '/');
		$this->status = $routeInfo[0];
		$this->handler = $routeInfo[1];
		$this->vars = $routeInfo[2];
	}

	private function isApiRoute(): bool
	{
		$uri = $this->getUri();

		return preg_match('/api/', $uri);
	}

	private function replaceRequest()
	{
		$parser = $this->requestParseFactory->make($this->request);
		$this->request = $parser->parse()->getRequest();
	}
}
