<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\BaseService;
use App\Service\RedisService;
use Respect\Validation\Validator as v;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'order:%s:user:%s';

    // todo create orderRepositoryInterface
    protected OrderRepository $orderRepository;

    protected RedisService $redisService;

    public function __construct(
        OrderRepository $orderRepository,
        RedisService $redisService
    ) {
        $this->orderRepository = $orderRepository;
        $this->redisService = $redisService;
    }

    protected function validateOrderId(string $id): int
    {
        if (!v::notEmpty()->validate($id) || !v::stringType()->validate($id) || (int)$id === 0) {
            throw new \App\Exception\Order('Invalid id', 400);
        }

        if ($this->isOrderExist($id)) {
            throw new \App\Exception\Order('Id already used', 400);
        }

        return (int)$id;
    }

    protected static function validateOrderUserId(string $userId): int
    {
        if (!v::notEmpty()->validate($userId) || !v::stringType()->validate($userId)) {
            throw new \App\Exception\Order('Invalid customer-id', 400);
        }

        return (int)$userId;
    }

    protected static function validateOrderTotal(string $total): float
    {
        if (!v::notEmpty()->validate($total) || !v::stringType()->validate($total)) {
            throw new \App\Exception\Order('Invalid total', 400);
        }

        return (float)$total;
    }

    protected static function validateOrderItems(?array $items): array
    {
        if (!v::notEmpty()->validate($items) || !v::arrayType()->validate($items)) {
            throw new \App\Exception\Order('Can not create order without items', 400);
        }

        return (array)$items;
    }

    protected function getOrderFromCache(int $orderId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $orderId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $order = $this->redisService->get($key);
        } else {
            $order = $this->getOrderFromDb($orderId)->toJson();
            $this->redisService->setex($key, $order);
        }

        return $order;
    }

    protected function getOrderFromDb(int $orderId): Order
    {
        return $this->getOrderRepository()->checkAndGetOrder($orderId);
    }

    protected function isOrderExist(string $orderId): bool
    {
        return $this->getOrderRepository()->isOrderExist((int)$orderId);
    }

    protected function getOrderRepository(): OrderRepository
    {
        return $this->orderRepository;
    }

    protected function saveInCache(int $orderId, int $userId, object $order): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $orderId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $order);
    }

    protected function deleteFromCache(int $orderId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $orderId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
