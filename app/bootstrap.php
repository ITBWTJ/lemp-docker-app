<?php

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions($rootDir . '/config/container.php');
$container = $builder->build();


