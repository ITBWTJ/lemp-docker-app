<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.11.18
 * Time: 20:28
 */

namespace Src\Http\Request;


use App\Http\Request;

class TextPlainParser extends RequestParser
{
    private $body;

    /**
     * @return RequestParser
     */
    public function parse(): RequestParser
    {
    	$this->appRequest = new Request($this->request->getMethod(), $this->request->getUri());
        $this->selectBody();
        $this->setRequestParams();

        return $this;
    }

    /**
     *
     */
    protected function selectBody(): void
    {
        $this->body = $this->request->getQueryParams();
    }

    /**
     *
     */
    protected function setRequestParams(): void
    {
        if (!empty($this->body)) {
            foreach ($this->body as $key => $value) {
                $this->appRequest->set($key, $value);
            }
        }
    }
}
