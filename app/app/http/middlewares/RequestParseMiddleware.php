<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.11.18
 * Time: 11:13
 */

namespace App\Http\Middlewares;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Relay\MiddlewareInterface;
use Spiral\Debug\Dumper;
use Src\Http\Request\RequestParseFactory;

/**
 * Class RequestParseMiddleware
 * @package App\Http\Middlewares
 */
class RequestParseMiddleware implements MiddlewareInterface
{
    /**
     * @var RequestParseFactory
     */
    private $factory;

    /**
     * RequestParseMiddleware constructor.
     * @param RequestParseFactory $factory
     */
    public function __construct(RequestParseFactory $factory )
    {
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable|null $next
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {

        $parser = $this->factory->make($request);
        $request = $parser->parse()->getRequest();

        return $next($request, $response);
    }
}
