<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15.11.18
 * Time: 20:27
 */

namespace App\Exceptions\Controller;


class ControllerIsNotExistsException extends ControllerException
{
    /**
     * ControllerIsNotExistsException constructor.
     * @param string $controller
     */
    public function __construct(string $controller)
    {
        parent::__construct("Controller $controller doesnt exists");
    }
}