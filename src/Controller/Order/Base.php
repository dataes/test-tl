<?php

declare(strict_types=1);

namespace App\Controller\Order;

use App\Controller\BaseController;
use App\Service\Order\OrderService;
use App\Service\Product\ProductService;
use App\Service\User\Find;

abstract class Base extends BaseController
{
    // ! note : you can create order for another user as shown in the sample example;
    // but you can get and delete only your orders,

    protected function getOrderService(): OrderService
    {
        return $this->container->get('order_service');
    }

    protected function getProductService(): ProductService
    {
        return $this->container->get('product_service');
    }

    protected function getFindUserService(): Find
    {
        return $this->container->get('find_user_service');
    }

    protected function getAndValidateUserId(array $input): int
    {
        if (isset($input['decoded']) && isset($input['decoded']->sub)) {
            return (int) $input['decoded']->sub;
        }

        throw new \App\Exception\Order('Invalid user. Permission failed.', 400);
    }
}
