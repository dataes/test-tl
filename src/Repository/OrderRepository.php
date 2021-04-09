<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;

final class OrderRepository extends BaseRepository
{
    public function getOrdersByPage(
        int $page,
        int $perPage,
        ?string $total
    ): array {
        $params = [
            'total' => is_null($total) ? '' : $total
        ];
        $query = $this->getQueryOrdersByPage();
        $statement = $this->database->prepare($query);
        $statement->bindParam('total', $params['total']);
        $statement->execute();
        $totalResult = $statement->rowCount();

        return $this->getResultsWithPagination(
            $query,
            $page,
            $perPage,
            $params,
            $totalResult
        );
    }

    public function getQueryOrdersByPage(): string
    {
        return "
            SELECT *
            FROM `orders`
            WHERE `total` LIKE CONCAT('%', :total, '%')
            ORDER BY `id`
        ";
    }

    public function getAllOrders(): array
    {
        $query = 'SELECT * FROM `orders` ORDER BY `id`';
        $statement = $this->getDb()->prepare($query);
        $statement->execute();

        return (array)$statement->fetchAll();
    }

    public function create(Order $order): Order
    {
        $query = '
            INSERT INTO `orders`
                (`id`, `total`, `user_id`)
            VALUES
                (:id, :total, :userId)
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $order->getId();
        $total = $order->getTotal();
        $userId = $order->getUserId();
        $statement->bindParam('id', $id);
        $statement->bindParam('total', $total);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        $id = (int)$this->database->lastInsertId();

        // insert in pivot table
        $query = '
            INSERT INTO `product_has_order`
                (`product_id`, `order_id`, `price`, `quantity`)
            VALUES
                (:product_id, :order_id, :price, :quantity)
        ';

        foreach ($order->getProducts() as $product) {
            $statement = $this->getDb()->prepare($query);
            $productId = $product->id;
            $orderId = $order->getId();
            $price = $product->price;
            $quantity = $product->quantity;
            $statement->bindParam('product_id', $productId);
            $statement->bindParam('order_id', $orderId);
            $statement->bindParam('price', $price);
            $statement->bindParam('quantity', $quantity);
            $statement->execute();
        }

        return $this->checkAndGetOrder((int)$id);
    }

    public function checkAndGetOrder(int $id): Order
    {
        $query = '
            SELECT * FROM `orders` WHERE `id` = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $id);
        $statement->execute();
        $order = $statement->fetchObject(Order::class);
        if (!$order) {
            throw new \App\Exception\Order('Order not found.', 404);
        }
        // todo join products linked in the get order result
        return $order;
    }

    public function isOrderExist(int $id): bool
    {
        $query = '
            SELECT `id` FROM `orders` WHERE `id` = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $id);
        $statement->execute();
        $order = $statement->fetchObject(Order::class);
        if (!$order) {
            return false;
        }
        return true;
    }

    public function update(Order $order): Order
    {
//        $query = '
//            UPDATE `orders`
//            SET `total` = :total
//            WHERE `id` = :id AND `user_id` = :userId
//        ';
//        $statement = $this->getDb()->prepare($query);
//        $id = $order->getId();
//        $total = $order->getTotal();
//        $userId = $order->getUserId();
//        $statement->bindParam('id', $id);
//        $statement->bindParam('total', $total);
//        $statement->bindParam('userId', $userId);
//        $statement->execute();
//
//        return $this->checkAndGetOrder((int)$id);
    }

    public function delete(int $orderId): void
    {
        $query = 'DELETE FROM `product_has_order` WHERE `order_id` = :orderId';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('orderId', $orderId);
        $statement->execute();

        $query = 'DELETE FROM `orders` WHERE `id` = :id';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $orderId);
        $statement->execute();
    }
}
