<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.11.18
 * Time: 20:20
 */

namespace Src\Http\Request;


class JsonParser extends RequestParser
{
    private $body;

    /**
     * @return RequestParser
     */
    public function parse(): RequestParser
    {
        $this->selectBody();
        $this->setRequestParams();

        return $this;
    }

    /**
     *
     */
    protected function selectBody(): void
    {
        $body = $this->request->getBody();

        $this->body = json_decode($body, 1);
    }

    /**
     *
     */
    protected function setRequestParams(): void
    {
        if (!empty($this->body)) {
            foreach ($this->body as $key => $value) {
                $this->request->set($key, $value);
            }
        }
    }
}