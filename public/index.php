<?php 

declare(strict_types=1);

$publicDir = __DIR__;
$rootDir = dirname($publicDir);

require_once($rootDir .'/vendor/autoload.php');
require_once($rootDir .'/app/bootstrap.php');
//dd($GLOBALS, getallheaders());
require_once($rootDir .'/config/routes.php');

//$middlewareQueue[] = new \Middlewares\FastRoute($container->get('router'));
//$middlewareQueue[] = new \Middlewares\RequestHandler($container);
//$middlewareQueue[] = new \App\Http\middlewares\ApiMiddleware();
//$middlewareQueue[] = new \App\Http\middlewares\ApiMiddleware2();


$kernel = $container->get('kernel');
$kernel->run();
