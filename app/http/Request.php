<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 12:32
 */

namespace App\Http;

class Request extends \GuzzleHttp\Psr7\Request
{
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

        parent::__construct($method, $uri, $headers, $body, $version);
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