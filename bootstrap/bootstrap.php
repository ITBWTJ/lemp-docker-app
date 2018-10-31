<?php

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions($rootDir . '/config/container.php');
$container = $builder->build();
container($container);


require_once 'doctrine.php';
$container->set(\Doctrine\ORM\EntityManager::class, $entity_manager);