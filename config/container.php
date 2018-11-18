<?php

use App\Controllers\{HomeController, AdminController};
use App\Controllers\Api\{UserController};
use App\Http\Middlewares\{RouteMiddleware, MiddlewareContainer};
use Psr\Container\ContainerInterface;
use Relay\RelayBuilder;
use Src\Http\Middleware\MiddlewareMediator;
use FastRoute\Dispatcher\GroupCountBased as router;
use FastRoute\DataGenerator\GroupCountBased;

return [
    'routeParser' => new FastRoute\RouteParser\Std(),
    'dataGenerator' => new GroupCountBased(),
    'routerCollection' => function (ContainerInterface $c) {
        return new \FastRoute\RouteCollector($c->get('routeParser'), $c->get('dataGenerator'));
    },
    'router' => function (ContainerInterface $c) {
        $collector = $c->get('routerCollection');
        return new router($collector->getData());
    },
    'handler' => function (ContainerInterface $c) {
        return new \App\Http\Handler($c->get('router'), $c->get('request'), $c->get('response'));
    },
    'kernel' => function (ContainerInterface $c) {
        return new \App\Kernel($c->get(RelayBuilder::class), $c->get('handler'), $c->get(MiddlewareMediator::class));
    },
    RelayBuilder::class => new RelayBuilder(),
    'request' => function(ContainerInterface $c) {
        return new \App\Http\Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], \getallheaders(), $c->get('stream'), $_SERVER['SERVER_PROTOCOL']);
    },
    'response' => new \App\Http\Response(),
    'stream' => new \App\Http\Stream(),
    HomeController::class => function(ContainerInterface $c) {
        return new HomeController($c->get('request'), $c->get('response'));
    },
    AdminController::class => function(ContainerInterface $c) {
        return new AdminController($c->get('request'), $c->get('response'));
    },
    UserController::class => function(ContainerInterface $c) {
        return new UserController($c->get('request'), $c->get('response'));
    },
    \App\Controllers\Auth\LoginController::class => function(ContainerInterface $c) {
        return new \App\Controllers\Auth\LoginController($c->get('request'), $c->get('response'));
    },
    RouteMiddleware::class => new RouteMiddleware(),

    MiddlewareContainer::class => new MiddlewareContainer(),

    MiddlewareMediator::class => function (ContainerInterface $c) {
        return new MiddlewareMediator($c->get(MiddlewareContainer::class));
    }


];