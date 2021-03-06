<?php

declare(strict_types=1);

use App\Controller\Product;
use App\Controller\Order;
use App\Controller\User;
use App\Middleware\Auth;

/** @var \Slim\App $app */

$app->get('/', 'App\Controller\DefaultController:getHelp');
$app->get('/status', 'App\Controller\DefaultController:getStatus');
$app->post('/login', \App\Controller\User\Login::class);

$app->group('/api/v1', function () use ($app): void {
    $app->group('/users', function () use ($app): void {
        $app->get('', User\GetAll::class)->add(new Auth());
        $app->post('', User\Create::class);
        $app->get('/{id}', User\GetOne::class)->add(new Auth());
        $app->put('/{id}', User\Update::class)->add(new Auth());
        $app->delete('/{id}', User\Delete::class)->add(new Auth());
    });

    $app->group('/products', function () use ($app): void {
        $app->get('', Product\GetAll::class);
        $app->post('', Product\Create::class);
        $app->get('/{id}', Product\GetOne::class);
        $app->put('/{id}', Product\Update::class);
        $app->delete('/{id}', Product\Delete::class);
    })->add(new Auth());

    $app->group('/orders', function () use ($app): void {
        $app->get('', Order\GetAll::class);
        $app->post('', Order\Create::class);
        $app->get('/{id}', Order\GetOne::class);
//        $app->put('/{id}', Order\Update::class); todo
        $app->delete('/{id}', Order\Delete::class);
    })->add(new Auth());
});
