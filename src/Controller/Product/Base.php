<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Controller\BaseController;
use App\Service\Product\ProductService;

abstract class Base extends BaseController
{
    protected function getProductService(): ProductService
    {
        return $this->container->get('product_service');
    }

    protected function getAndValidateUserId(array $input): int
    {
        if (isset($input['decoded']) && isset($input['decoded']->sub)) {
            return (int) $input['decoded']->sub;
        }

        throw new \App\Exception\Product('Invalid user. Permission failed.', 400);
    }
}
