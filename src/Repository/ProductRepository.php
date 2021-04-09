<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;

final class ProductRepository extends BaseRepository
{
    public function getProductsByPage(
        int $page,
        int $perPage,
        ?string $id,
        ?string $description,
        ?int $category,
        ?float $price
    ): array {
        $params = [
            'id' => is_null($id) ? '' : $id,
            'description' => is_null($description) ? '' : $description,
            'category' => is_null($category) ? '' : $category,
            'price' => is_null($price) ? '' : $price,
        ];
        $query = $this->getQueryProductsByPage();
        $statement = $this->database->prepare($query);
        $statement->bindParam('id', $params['id']);
        $statement->bindParam('description', $params['description']);
        $statement->bindParam('category', $params['category']);
        $statement->bindParam('price', $params['price']);
        $statement->execute();
        $total = $statement->rowCount();

        return $this->getResultsWithPagination(
            $query,
            $page,
            $perPage,
            $params,
            $total
        );
    }

    public function getQueryProductsByPage(): string
    {
        return "
            SELECT *
            FROM `products`
            WHERE `id` LIKE CONCAT('%', :id, '%')
            AND `description` LIKE CONCAT('%', :description, '%')
            AND `category` LIKE CONCAT('%', :category, '%')
            AND `price` LIKE CONCAT('%', :price, '%')
            ORDER BY `id`
        ";
    }

    public function getAllProducts(): array
    {
        $query = 'SELECT * FROM `products` ORDER BY `id`';
        $statement = $this->getDb()->prepare($query);
        $statement->execute();

        return (array)$statement->fetchAll();
    }

    public function create(Product $product): Product
    {
        $query = '
            INSERT INTO `products`
                (`id`, `description`, `category`, `price`)
            VALUES
                (:id, :description, :category, :price)
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $product->getId();
        $desc = $product->getDescription();
        $category = $product->getCategory();
        $price = $product->getPrice();
        $statement->bindParam('id', $id);
        $statement->bindParam('description', $desc);
        $statement->bindParam('category', $category);
        $statement->bindParam('price', $price);
        $statement->execute();

        return $this->checkAndGetProduct((string)$id);
    }

    public function checkAndGetProduct(string $id): Product
    {
        $query = '
            SELECT * FROM `products` WHERE `id` = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $id);
        $statement->execute();
        $product = $statement->fetchObject(Product::class);
        if (!$product) {
            throw new \App\Exception\Product('Product ' . $id . ' not found.', 404);
        }

        return $product;
    }

    public function update(Product $product): Product
    {
        $query = '
            UPDATE `products`
            SET `description` = :description, `category` = :category, `price` = :price
            WHERE `id` = :id
        ';
        $statement = $this->getDb()->prepare($query);
        $id = $product->getId();
        $desc = $product->getDescription();
        $category = $product->getCategory();
        $price = $product->getPrice();
        $statement->bindParam('id', $id);
        $statement->bindParam('description', $desc);
        $statement->bindParam('category', $category);
        $statement->bindParam('price', $price);
        $statement->execute();

        return $this->checkAndGetProduct((string)$id);
    }

    public function delete(string $productId): void
    {
        $query = 'DELETE FROM `products` WHERE `id` = :id';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $productId);
        $statement->execute();
    }
}
