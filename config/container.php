<?php

return [
    'routeParser' => new FastRoute\RouteParser\Std(),
    'dataGenerator' => new \FastRoute\DataGenerator\GroupCountBased(),
    'routerCollection' => function (\Psr\Container\ContainerInterface $c) {
        return new \FastRoute\RouteCollector($c->get('routeParser'), $c->get('dataGenerator'));
    },
    'router' => function (\Psr\Container\ContainerInterface $c) {
        $collector = $c->get('routerCollection');
        return new \FastRoute\Dispatcher\GroupCountBased($collector->getData());
    },
    'kernel' => function (\Psr\Container\ContainerInterface $c) {
        return new \App\Kernel($c->get('router'));
    },

];