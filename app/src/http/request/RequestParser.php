<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.11.18
 * Time: 11:16
 */

namespace Src\Http\Request;

use App\Http\Request;

abstract class RequestParser
{
    /**
     * @var Request
     */
    protected $request;

    protected $appRequest;

    /**
     * @return mixed
     */
    public function getRequest(): \Psr\Http\Message\RequestInterface
    {
        return $this->appRequest;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return RequestParser
     */
    public function setRequest(\Psr\Http\Message\RequestInterface $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return RequestParser
     */
    abstract public function parse(): self;

    /**
     *
     */
    abstract protected function selectBody(): void;

    /**
     *
     */
    abstract protected function setRequestParams(): void;
    /**
     *
     */
}
