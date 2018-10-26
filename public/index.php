<?php 

$publicDir = __DIR__;
$rootDir = dirname($publicDir);

require_once($rootDir .'/vendor/autoload.php');
require_once($rootDir .'/app/bootstrap.php');
require_once($rootDir .'/config/routes.php');

$kernel = $container->get('kernel');
$kernel->run();
