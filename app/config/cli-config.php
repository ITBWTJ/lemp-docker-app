<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$publicDir = __DIR__;
$rootDir = dirname($publicDir);

require_once $rootDir . '/bootstrap/doctrine.php';

return ConsoleRunner::createHelperSet($entity_manager);