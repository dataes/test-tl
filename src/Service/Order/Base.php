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

    protected static function validateOrderTotal(float $total): float
    {
        if (!v::floatVal()->validate($total)) {
            throw new \App\Exception\Order('Invalid total', 400);
        }

        return $total;
    }

    protected function getOrderFromCache(int $orderId, int $userId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $orderId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $order = $this->redisService->get($key);
        } else {
            $order = $this->getOrderFromDb($orderId, $userId)->toJson();
            $this->redisService->setex($key, $order);
        }

        return $order;
    }

    protected function getOrderFromDb(int $orderId, int $userId): Order
    {
        return $this->getOrderRepository()->checkAndGetOrder($orderId, $userId);
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

    protected function deleteFromCache(int $orderId, int $userId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $orderId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
