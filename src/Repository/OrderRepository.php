<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;

final class OrderRepository extends BaseRepository
{
    public function getOrdersByPage(
        int $userId,
        int $page,
        int $perPage,
        ?string $total
    ): array {
        $params = [
            'user_id' => $userId,
            'total' => is_null($total) ? '' : $total
        ];
        $query = $this->getQueryOrdersByPage();
        $statement = $this->database->prepare($query);
        $statement->bindParam('user_id', $params['user_id']);
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
            WHERE `user_id` = :user_id
            AND `total` LIKE CONCAT('%', :total, '%')
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
                (`total`, `user_id`)
            VALUES
                (:total, :userId)
        ';
        $statement = $this->getDb()->prepare($query);
        $total = $order->getTotal();
        $userId = $order->getUserId();
        $statement->bindParam('total', $total);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        $id = (int)$this->database->lastInsertId();

        return $this->checkAndGetOrder((int)$id, (int) $userId);
    }

    public function checkAndGetOrder(int $id, int $userId): Order
    {
        $query = '
            SELECT * FROM `orders` WHERE `id` = :id AND `user_id` = :userId
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $id);
        $statement->bindParam('userId', $userId);
        $statement->execute();
        $order = $statement->fetchObject(Order::class);
        if (!$order) {
            throw new \App\Exception\Order('Order not found.', 404);
        }

        // todo add product items to the order result
        return $order;
    }

    public function update(Order $order): Order
    {
        $query = '
            UPDATE `orders`
            SET `total` = :total
            WHERE `id` = :id AND `user_id` = :userId
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $order->getId();
        $total = $order->getTotal();
        $userId = $order->getUserId();
        $statement->bindParam('id', $id);
        $statement->bindParam('total', $total);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        return $this->checkAndGetOrder((int)$id, (int) $userId);
    }

    public function delete(int $orderId,  int $userId): void
    {
        $query = 'DELETE FROM `orders` WHERE `id` = :id AND `user_id` = :userId';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $orderId);
        $statement->bindParam('userId', $userId);
        $statement->execute();
    }
}
