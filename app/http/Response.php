<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.10.18
 * Time: 1:33
 */

namespace App\Http;


use function GuzzleHttp\Psr7\stream_for;

class Response extends \GuzzleHttp\Psr7\Response
{
    /**
     * Response constructor.
     * @param int $status
     * @param array $headers
     * @param null $body
     * @param string $version
     * @param null $reason
     */
    public function __construct($status = 200, array $headers = [], $body = null, $version = '1.1', $reason = null)
    {
        parent::__construct($status, $headers, $body, $version, $reason);
    }

    /**
     *
     */
    public function send()
    {
        foreach ($this->getHeaders() as $header => $value) {
            header($header . ': ' . $value[0], false, $this->getStatusCode());
        }

        echo $this->getBody();
    }

    /**
     * @param string $body
     * @return $this
     */
    public function withStrToBody(string $body): self
    {
        $new = clone $this;
        $new = $new->withBody(stream_for($body));

        return $new;
    }

}