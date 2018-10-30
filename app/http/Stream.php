<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.10.18
 * Time: 11:04
 */

namespace App\Http;


/**
 * Class Stream
 * @package App\Http
 */
class Stream extends \GuzzleHttp\Psr7\Stream
{
    /**
     * Stream constructor.
     */
    public function __construct()
    {
        parent::__construct(fopen('php://stdin', 'r'));
    }
}