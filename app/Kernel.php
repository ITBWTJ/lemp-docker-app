<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 12:31
 */

namespace App;

use App\Http\Handler;
use App\Http\Request;
use \FastRoute\Dispatcher\GroupCountBased;

class Kernel
{
    /**
     * @var
     */
    private $handler;

    /**
     * Kernel constructor.
     * @param Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function run()
    {
        $respons = $this->handler->handle();
    }



}