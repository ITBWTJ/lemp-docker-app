<?php

$collection = $container->get('routerCollection');

$collection->addRoute('GET', '/', 'App\Controllers\HomeController@index');

//$collection->addRoute('GET', '/admin', 'App\Controllers\AdminController@index');

$collection->addGroup('/auth', function (\FastRoute\RouteCollector $c) {
   $c->addRoute('POST', '/login', 'App\Controllers\Auth\LoginController@login');
   $c->addRoute('POST', '/register', 'App\Controllers\Auth\RegistrationController@register');
});

$collection->addRoute('GET', '/test', 'App\Controllers\TestController@test');


$collection->addGroup('/api', function (\FastRoute\RouteCollector $c) {

    // USER RESET API
    $c->addRoute('GET', '/users', 'App\Controllers\Api\UserController@index');

    $c->addRoute('GET', '/users/me', 'App\Controllers\Api\UserController@getUserByToken');

    $c->addRoute('GET', '/users/{id:\d+}', 'App\Controllers\Api\UserController@show');

    $c->addRoute('POST', '/users', 'App\Controllers\Api\UserController@store');

    $c->addRoute('PUT', '/users/{id:\d+}', 'App\Controllers\Api\UserController@update');

    $c->addRoute('DELETE', '/users/{id:\d+}', 'App\Controllers\Api\UserController@delete');

    // POST REST API
    $c->addRoute('GET', '/posts', 'App\Controllers\Api\PostController@index');

    $c->addRoute('GET', '/posts/{id:\d+}', 'App\Controllers\Api\PostController@show');

    $c->addRoute('POST', '/posts', 'App\Controllers\Api\PostController@store');

    $c->addRoute('PUT', '/posts/{id:\d+}', 'App\Controllers\Api\PostController@update');

    $c->addRoute('DELETE', '/posts/{id:\d+}', 'App\Controllers\Api\PostController@delete');

    // POST REST API
    $c->addRoute('GET', '/sms', 'App\Controllers\Api\SmsSendingController@index');

    $c->addRoute('GET', '/sms/{id:\d+}', 'App\Controllers\Api\SmsSendingController@show');

    $c->addRoute('POST', '/sms', 'App\Controllers\Api\SmsSendingController@store');

    $c->addRoute('PUT', '/sms/{id:\d+}', 'App\Controllers\Api\SmsSendingController@update');

    $c->addRoute('DELETE', '/sms/{id:\d+}', 'App\Controllers\Api\SmsSendingController@delete');
});








