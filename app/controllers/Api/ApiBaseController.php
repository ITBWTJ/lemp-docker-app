<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.18
 * Time: 23:44
 */

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class ApiBaseController extends BaseController
{
    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return [
            'api',
        ];
    }
}