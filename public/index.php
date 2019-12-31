<?php

declare(strict_types=1);
//echo phpinfo();die();
xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
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


$xhprof_data = xhprof_disable();

include_once __DIR__.'/../xhprof_lib/utils/xhprof_lib.php';
include_once __DIR__.'/../xhprof_lib/utils/xhprof_runs.php';

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, 'test');

