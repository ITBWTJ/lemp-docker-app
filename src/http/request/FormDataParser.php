<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.11.18
 * Time: 20:25
 */

namespace Src\Http\Request;


class FormDataParser extends RequestParser
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->body = $_POST;
        } else {
            parse_str(file_get_contents("php://input"),$post_vars);
            $this->body = $post_vars;
        }
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