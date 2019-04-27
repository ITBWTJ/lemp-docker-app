<?php

// Setup Doctrine
$configuration = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
    $paths = [$rootDir . '/app/entities'],
    $isDevMode = true
);

// Setup connection parameters
$connection_parameters = require $rootDir . '/config/database.php';

// Get the entity manager
$entity_manager = \Doctrine\ORM\EntityManager::create($connection_parameters, $configuration);