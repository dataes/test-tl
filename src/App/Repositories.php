<?php

declare(strict_types=1);

use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;

$container['user_repository'] = static fn(ContainerInterface $container): UserRepository => new UserRepository(
    $container->get('db')
);

$container['product_repository'] = static fn(ContainerInterface $container): ProductRepository => new ProductRepository(
    $container->get('db')
);

$container['order_repository'] = static fn(ContainerInterface $container): OrderRepository => new OrderRepository(
    $container->get('db')
);
