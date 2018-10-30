<?php

$collection = $container->get('routerCollection');


$collection->addRoute('GET', '/', 'App\Controllers\HomeController@index');

$collection->addRoute('GET', '/admin', 'App\Controllers\AdminController@index');

$collection->addRoute('GET', '/api/users', 'App\Controllers\Api\UserController@index');




