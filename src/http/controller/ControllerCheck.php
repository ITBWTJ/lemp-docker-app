<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.11.18
 * Time: 21:04
 */

namespace Src\Http\Controller;

use App\Exceptions\Controller\{ControllerIsNotExistsException, ControllerMethodIsNotExistsException};


trait ControllerCheck
{
    /**
     * @param string $controller
     * @return bool
     * @throws ControllerIsNotExistsException
     */
    public function controllerExists(string $controller)
    {
        $container = container();
        if (!$container->has($controller)) {
            throw new ControllerIsNotExistsException($controller);
        }

        return true;
    }

    /**
     * @param \App\Controllers\BaseController $controller
     * @param string $method
     * @return bool
     * @throws ControllerMethodIsNotExistsException
     */
    public function methodExists(\App\Controllers\BaseController $controller, string $method)
    {
        if (!method_exists($controller, $method)) {
            throw new ControllerMethodIsNotExistsException($controller, $method);
        }

        return true;
    }
}