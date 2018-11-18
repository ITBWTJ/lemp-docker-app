<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.11.18
 * Time: 19:49
 */

namespace App\Exceptions\Controller;

class ControllerMethodIsNotExistsException extends ControllerException
{
    /**
     * ControllerMethodIsNotExistsException constructor.
     * @param string $controller
     * @param string $method
     */
    public function __construct(string $controller, string $method)
    {
        parent::__construct("Controller $controller not has method $method");
    }
}