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
use Spiral\Debug\Dumper;
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
	 * @var \App\Http\Request
	 */
	private $request;

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
		$this->request = $request;
		$this->parseHandler($this->request->getHandler());

		try {
			$response = $this->runController();

			return $response;
		} catch (ControllerException $e) {
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
//		$this->container->set(\App\Http\Request::class, $this->request);
		$this->controller = $this->container->get($this->controller);

		$this->methodExists($this->controller, $this->method);
		$dumper = new Dumper();
		$dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());
		$dumper->dump('CM request', Dumper::ERROR_LOG);
		$dumper->dump($this->request, Dumper::ERROR_LOG);
		$args = ['request' => $this->request];
		$args += $this->request->getArgs();
		return call_user_func_array([$this->controller, $this->method], $args);
	}
}
