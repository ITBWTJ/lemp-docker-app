<?php

$collection = $container->get('routerCollection');

$collection->addRoute('GET', '/', 'App\Controllers\HomeController@index');

$collection->addRoute('GET', '/admin', 'App\Controllers\AdminController@index');

$collection->addGroup('/auth', function (\FastRoute\RouteCollector $c) {
   $c->addRoute('GET', '/login', 'App\Controllers\Auth\LoginController@loginForm');
   $c->addRoute('POST', '/login', 'App\Controllers\Auth\LoginController@login');
});

$collection->addGroup('/api', function (\FastRoute\RouteCollector $c) {
    $c->addRoute('GET', '/users', 'App\Controllers\Api\UserController@index');

    $c->addRoute('GET', '/users/{id:\d+}', 'App\Controllers\Api\UserController@show');

    $c->addRoute('POST', '/users', 'App\Controllers\Api\UserController@store');

    $c->addRoute('PUT', '/users/{id:\d+}', 'App\Controllers\Api\UserController@update');

    $c->addRoute('DELETE', '/users/{id:\d+}', 'App\Controllers\Api\UserController@delete');
});






