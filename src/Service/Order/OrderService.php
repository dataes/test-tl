<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;

final class OrderService extends Base
{
    public function getOrdersByPage(
        int $userId,
        int $page,
        int $perPage,
        ?float $total
    ): array {
        if ($page < 1) {
            $page = 1;
        }
        if ($perPage < 1) {
            $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
        }

        return $this->getOrderRepository()->getOrdersByPage(
            $userId,
            $page,
            $perPage,
            $total
        );
    }

    public function getAllOrders(): array
    {
        return $this->getOrderRepository()->getAllOrders();
    }

    public function getOne(int $orderId, int $userId): object
    {
        if (self::isRedisEnabled() === true) {
            $order = $this->getOrderFromCache($orderId, $userId);
        } else {
            $order = $this->getOrderFromDb($orderId, $userId)->toJson();
        }

        return $order;
    }

    public function create(array $input): object
    {
        // transform array into an object
        $data = json_decode((string)json_encode($input), false);
        if (!isset($data->total)) {
            throw new \App\Exception\Order('The field "total" is required.', 400);
        }
        // it could be an order from the connected user like : $myOrder->setUserId((int)$data->decoded->sub);
        if (!isset($data->{'customer-id'})) {
            throw new \App\Exception\Order('The field "customer-id" is required.', 400);
        }
        $myOrder = new Order();
        $myOrder->setId($this->validateOrderId($data->id));
        $myOrder->setUserId(self::validateOrderUserId($data->{'customer-id'}));
        $myOrder->setTotal(self::validateOrderTotal($data->total));
        $myOrder->setProducts(self::validateOrderItems($data->items));

        /** @var Order $order */
        $order = $this->getOrderRepository()->create($myOrder);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($order->getId(), $order->getUserId(), $order->toJson());
        }

        return $order->toJson();
    }

    // TODO

//    public function update(array $input, int $orderId): object
//    {
//        $data = $this->validateOrder($input, $orderId);
//        /** @var Order $order */
//        $order = $this->getOrderRepository()->update($data);
//        if (self::isRedisEnabled() === true) {
//            $this->saveInCache($order->getId(), (int)$data->getUserId(), $order->toJson());
//        }
//
//        return $order->toJson();
//    }
//
//    private function validateOrder(array $input, int $orderId): Order
//    {
//        $order = $this->getOrderFromDb($orderId, (int)$input['decoded']->sub);
//        $data = json_decode((string)json_encode($input), false);
//        if (!isset($data->total)) {
//            throw new \App\Exception\Order('Enter the data to update the order.', 400);
//        }
//        $order->setUserId((int)$data->decoded->sub);
//
//        return $order;
//    }
//
    public function delete(int $orderId, int $userId): void
    {
        $this->getOrderFromDb($orderId, $userId);
        $this->getOrderRepository()->delete($orderId, $userId);
        if (self::isRedisEnabled() === true) {
            $this->deleteFromCache($orderId, $userId);
        }
    }
}
