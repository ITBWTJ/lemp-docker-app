<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.11.18
 * Time: 20:53
 */

namespace Src\Http\Request;


trait ParseHandler
{
    /*
      *
      */
    private function parseHandler($handler): void
    {
        $exploded = explode('@', $handler);
        $this->controller = $exploded[0];
        $this->method = $exploded[1];
    }
}