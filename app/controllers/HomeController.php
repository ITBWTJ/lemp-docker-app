<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.10.18
 * Time: 18:21
 */

namespace App\Controllers;
use App\Http\Request;
use App\Http\Response;
use App\Http\Stream;
use function GuzzleHttp\Psr7\stream_for;

/**\
 * Class HomeController
 */
class HomeController extends BaseController
{

    /**
     * @return mixed
     */
    public function index()
    {
        $body = [
            'text' => 'Yes, it is home page',
        ];

        return $this->json($body);
    }
}