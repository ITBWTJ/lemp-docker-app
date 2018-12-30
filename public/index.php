<?php

declare(strict_types=1);


ob_start();

$publicDir = __DIR__;
$rootDir = dirname($publicDir);

define('__ROOT__', $rootDir);

require_once($rootDir .'/vendor/autoload.php');
require_once($rootDir . '/bootstrap/bootstrap.php');
//dd($GLOBALS, getallheaders());
require_once($rootDir .'/config/routes.php');


$kernel = $container->get('kernel');
$kernel->run();
