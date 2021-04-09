<?php

declare(strict_types=1);

use App\Service\Product\ProductService;
use App\Service\Order\OrderService;
use App\Service\User;
use Psr\Container\ContainerInterface;

$container['find_user_service'] = static fn (ContainerInterface $container): User\Find => new User\Find(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['create_user_service'] = static fn (ContainerInterface $container): User\Create => new User\Create(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['update_user_service'] = static fn (ContainerInterface $container): User\Update => new User\Update(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['delete_user_service'] = static fn (ContainerInterface $container): User\Delete => new User\Delete(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['login_user_service'] = static fn (ContainerInterface $container): User\Login => new User\Login(
    $container->get('user_repository'),
    $container->get('redis_service')
);

$container['product_service'] = static fn (ContainerInterface $container): ProductService => new ProductService(
    $container->get('product_repository'),
    $container->get('redis_service')
);

$container['order_service'] = static fn (ContainerInterface $container): OrderService => new OrderService(
    $container->get('order_repository'),
    $container->get('redis_service')
);