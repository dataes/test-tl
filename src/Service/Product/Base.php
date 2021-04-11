<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\BaseService;
use App\Service\RedisService;
use Respect\Validation\Validator as v;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'product:%s:user:%s';

    // todo create productRepositoryInterface
    protected ProductRepository $productRepository;

    protected RedisService $redisService;

    public function __construct(
        ProductRepository $productRepository,
        RedisService $redisService
    ) {
        $this->productRepository = $productRepository;
        $this->redisService = $redisService;
    }

    protected function validateProductId($id): string
    {
        if (!v::notEmpty()->validate($id) || !v::stringType()->validate($id)) {
            throw new \App\Exception\Product('Invalid id, must be string type not empty.', 400);
        }

        if ($this->isProductExist($id)) {
            throw new \App\Exception\Product('Product ID already used', 400);
        }

        return $id;
    }

    protected static function validateProductDescription(string $description): string
    {
        if (!v::length(1, 100)->validate($description)) {
            throw new \App\Exception\Product('Invalid description, too long.', 400);
        }

        return $description;
    }

    protected static function validateProductPrice($price): float
    {
        if (!v::notEmpty()->validate($price) || !v::stringType()->validate($price)) {
            throw new \App\Exception\Product('Invalid price, must be string type not empty..', 400);
        }

        return (float) $price;
    }

    protected static function validateProductCategory($category): int
    {
        // todo isCategoryExist()
        if (!v::notEmpty()->validate($category) || !v::stringType()->validate($category)) {
            throw new \App\Exception\Product('Invalid category, must be string type not empty.', 400);
        }

        return (int) $category;
    }

    protected function getProductFromCache(string $productId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $productId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $product = $this->redisService->get($key);
        } else {
            $product = $this->getProductFromDb($productId)->toJson();
            $this->redisService->setex($key, $product);
        }

        return $product;
    }

    protected function getProductFromDb(string $productId): Product
    {
        return $this->getProductRepository()->checkAndGetProduct($productId);
    }

    protected function getProductRepository(): ProductRepository
    {
        return $this->productRepository;
    }

    protected function saveInCache(string $productId, object $product): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $productId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $product);
    }

    protected function deleteFromCache(string $productId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $productId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }

    protected function getAndValidateUserId(array $input): int
    {
        if (isset($input['decoded']) && isset($input['decoded']->sub)) {
            return (int) $input['decoded']->sub;
        }

        throw new \App\Exception\Product('Invalid user. Permission failed.', 400);
    }

    protected function isProductExist(string $productId): bool
    {
        return $this->getProductRepository()->isProductExist($productId);
    }
}
