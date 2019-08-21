<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 12:32
 */

namespace App\Http;

use Spiral\Debug\Dumper;

class Request extends \GuzzleHttp\Psr7\Request
{
    /**
     * @var
     */
    private $handler;

    /**
     * @var
     */
    private $args;


    /**
     * @var array
     */
    private $params = [];

    /**
     * Request constructor.
     * @param $method
     * @param $uri
     * @param array $headers
     * @param null $body
     * @param string $version
     */
    public function __construct($method, $uri, array $headers = [], $body = null, $version = '1.1')
    {
        if ($version !== '1.1') {
            $version = $this->parseVersion($version);
        }

      if (empty($method)) {
        $method = 'GET';
      }
        parent::__construct($method, $uri, $headers, $body, $version);
    }


    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param $handler
     * @param array $args
     */
    public function setHandler($handler, $args = []): void
    {
        $this->handler = $handler;
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }


    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!empty($this->params[$name])) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        if (!empty($this->params[$name])) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @return array|null
     */
    public function all(): ?array
    {
        return $this->params;
    }


    /**
     * @param $name
     * @return bool
     */
    public function has($name): bool
    {
        return array_key_exists($name, $this->params);
    }

    /**
     * @param $version
     * @return string
     */
    private function parseVersion($version)
    {
        $explode = explode('/', $version);

        if (!empty($explode[1])) {
            return $explode[1];
        } else {
            return '1.1';
        }
    }

}
