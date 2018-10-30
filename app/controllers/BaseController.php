<?php

namespace App\Controllers;


use App\Http\Request;
use App\Http\Response;
use function GuzzleHttp\Psr7\stream_for;
use Psr\Http\Message\ResponseInterface;

class BaseController
{
    /**
     * @var
     */
    protected $request;

    /**
     * @var
     */
    protected $response;

    /**
     * BaseController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    /**
     * @param string $body
     * @return \GuzzleHttp\Psr7\Stream
     */
    protected function getStream(string $body)
    {
        return stream_for($body);
    }


    /**
     * @param string $body
     * @param int $status
     * @return Response
     */
    protected function response(string $body = '', int $status = 200): Response
    {
        return $this->response
            ->withBody($this->getStream($body))
            ->withStatus($status)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8')
            ->withProtocolVersion($this->request->getProtocolVersion());
    }

    /**
     * @param string $body
     * @param int $status
     * @return Response
     */
    protected function json( $body = '', int $status = 200): Response
    {
        return $this->response
            ->withBody($this->getStream(json_encode($body)))
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json;')
            ->withProtocolVersion($this->request->getProtocolVersion());
    }







}