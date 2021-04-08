<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Entity\Product;

final class ProductService extends Base
{
    public function getProductsByPage(
        int $page,
        int $perPage,
        ?string $id,
        ?string $description,
        ?int $category,
        ?float $price
    ): array {
        if ($page < 1) {
            $page = 1;
        }
        if ($perPage < 1) {
            $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
        }

        return $this->getProductRepository()->getProductsByPage(
            $page,
            $perPage,
            $id,
            $description,
            $category,
            $price
        );
    }

    public function getAllProducts(): array
    {
        return $this->getProductRepository()->getAllProducts();
    }

    public function getOne(string $productId): object
    {
        if (self::isRedisEnabled() === true) {
            $product = $this->getProductFromCache($productId);
        } else {
            $product = $this->getProductFromDb($productId)->toJson();
        }

        return $product;
    }

    public function create(array $input): object
    {
        $data = json_decode((string)json_encode($input), false);
        // todo create multiple from an array
        if (!isset($data->id)) {
            throw new \App\Exception\Product('The field "id" is required.', 400);
        }
        if (!isset($data->category)) {
            throw new \App\Exception\Product('The field "category" is required.', 400);
        }
        $myProduct = new Product();
        $myProduct->setId(self::validateProductId($data->id));
        $myProduct->setDescription((string) $data->description);
        $myProduct->setCategory(self::validateProductCategory($data->category));
        $myProduct->setPrice(self::validateProductPrice($data->price));

        /** @var Product $product */
        try {
            $product = $this->getProductRepository()->create($myProduct);
        } catch (\Exception $e) {
            throw new \App\Exception\Product('Category does not exist.', 400);
        }

        if (self::isRedisEnabled() === true) {
            $this->saveInCache($product->getId(), $product->toJson());
        }

        return $product->toJson();
    }

    public function update(array $input, string $productId): object
    {
        $data = $this->validateProduct($input, $productId);
        /** @var Product $product */
        $product = $this->getProductRepository()->update($data);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($product->getId(), $product->toJson());
        }

        return $product->toJson();
    }

    private function validateProduct(array $input, string $productId): Product
    {
        $product = $this->getProductFromDb($productId);

        $data = json_decode((string)json_encode($input), false);
        if (!isset($data->id) && !isset($data->category)) {
            throw new \App\Exception\Product('Enter the data to update the product.', 400);
        }
        $product->setId($product->getId());
        $product->setCategory(self::validateProductCategory($data->category));

        if (isset($data->description)) {
            $product->setDescription(self::validateProductDescription($data->description));
        } else {
            $product->setDescription("");
        }
        if (isset($data->price)) {
            $product->setPrice(self::validateProductPrice($data->price));
        } else {
            $product->setPrice(0.00);
        }

        return $product;
    }

    public function delete(string $productId): void
    {
        $this->getProductFromDb($productId);
        $this->getProductRepository()->delete($productId);
        if (self::isRedisEnabled() === true) {
            $this->deleteFromCache($productId);
        }
    }
}
