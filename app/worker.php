<?php
declare(strict_types=1);
ob_start();

$publicDir = __DIR__;
$rootDir = $publicDir;

define('__ROOT__', $rootDir);
ini_set('display_errors', 'stderr');
require_once($publicDir . '/vendor/autoload.php');
require_once($publicDir . '/bootstrap/bootstrap.php');
//dd($GLOBALS, getallheaders());
require_once($publicDir . '/config/routes.php');

use Spiral\Debug\Dumper;

$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));


//$dumper = new Dumper();
//$dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());

while ($req = $psr7->acceptRequest()) {
	try {
		container()->set('request', $req);
		$kernel = $container->get('kernel');
		$id = spl_object_id($kernel);
//		$dumper = new Dumper();
//		$dumper->setRenderer(Dumper::ERROR_LOG, new \Spiral\Debug\Renderer\ConsoleRenderer());
//		$dumper->dump('Request', Dumper::ERROR_LOG);
//		$dumper->dump($req, Dumper::ERROR_LOG);

		$resp = $kernel->run();

		$psr7->respond($resp);

	} catch (\Throwable $e) {
		$psr7->getWorker()->error((string)$e . ' \n' . $e->getTraceAsString());
	}
}
