<?php

use App\Controllers\{HomeController, AdminController};
use App\Controllers\Api\{UserController};
use App\Http\Middlewares\{RouteMiddleware, MiddlewareContainer};
use Psr\Container\ContainerInterface;
use Relay\RelayBuilder;

return [
    'routeParser' => new FastRoute\RouteParser\Std(),
    'dataGenerator' => new \FastRoute\DataGenerator\GroupCountBased(),
    'routerCollection' => function (ContainerInterface $c) {
        return new \FastRoute\RouteCollector($c->get('routeParser'), $c->get('dataGenerator'));
    },
    'router' => function (ContainerInterface $c) {
        $collector = $c->get('routerCollection');
        return new \FastRoute\Dispatcher\GroupCountBased($collector->getData());
    },
    'kernel' => function (ContainerInterface $c) {
        return new \App\Kernel($c->get(RelayBuilder::class), $c->get(MiddlewareContainer::class), $c->get('request'), $c->get('response'));
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
    RouteMiddleware::class => new RouteMiddleware(),

    MiddlewareContainer::class => new MiddlewareContainer(),


];