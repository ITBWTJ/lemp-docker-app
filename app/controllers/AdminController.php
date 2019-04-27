<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.10.18
 * Time: 18:34
 */

namespace App\Controllers;

use function GuzzleHttp\Psr7\stream_for;

/**
 * Class AdminController
 * @package App\Controllers
 */
class AdminController extends BaseController
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {

        $body = 'Yes, it is admin page';

        return $this->response($body);
    }
}