<?php

declare(strict_types=1);

namespace App\Service\Discount;

final class DiscountService
{
    private object $order;

    private array $discountMessages;

    // TODO : Unit tests (and use reflection to test private methods)

    public function getDiscounts(object $order): object
    {
        $this->order = $order;

        $this->order->total = $this->getDiscountOnTotal($order->total);
        $this->order->products = $this->getDiscountOnProducts($order->products);

        if (!empty($this->discountMessages)) {
            $this->order->discountMessages = $this->discountMessages;
        }

        return $this->order;
    }

    private function getDiscountOnTotal(float $total): float
    {
        switch ($total) {
            // A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
            case $total > 1000:
                $total = $total - ($total * 0.1);
                $this->discountMessages[] = 'You got 10% of discount because you already bought for over 1000';
                break;
        }
        return $total;
    }

    private function getDiscountOnProducts(array $products): array
    {
        // If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.
        if ($this->countProducts(1, $products) >= 2) {
            $products = $this->getDiscountOnCheapestProduct($products, 0.2);
            $this->discountMessages[] = 'You got 20% on cheapest product because you bought two or more products of category 1 (Tools)';
        }

        // For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
        if ($this->countProducts(2, $products) === 5) {
            $products[] = $this->getFreeProduct(2);
            $this->discountMessages[] = 'You got a free product of category 2 (Switches) because you bought five';
        }

        return $products;
    }

    private function countProducts(int $productCategoryId, array $products): int
    {
        $elements = [];
        foreach ($products as $product) {
            if ($product->category === $productCategoryId) {
                for ($i = 0; $i < $product->quantity; $i++) {
                    $elements[] = $productCategoryId;
                }
            }
        }

        return count($elements);
    }

    /* NOTE :
     * - I assume it's the cheapest product in all categories confounded
     * - With the quantity prop I assume that I can not have multiple time the same product-id from the request
     */
    private function getDiscountOnCheapestProduct(array $products, float $discount): array
    {
        $cheapestProduct = $this->getCheapestProduct($products);
        // search the product in the products array and add a the discount prop on the cheapest one
        return array_map(
            function ($product) use ($cheapestProduct, $discount) {
                if ($product->{'product-id'} === $cheapestProduct->{'product-id'}) {
                    $product->discount = $product->{'unit-price'} * $discount;
                    // subtract to total
                    $this->order->total = $this->order->total - $product->discount;
                    return $product;
                }
                return $product;
            }, $products
        );
    }

    private function getCheapestProduct(array $products): object
    {
        $cheapestPrice = min(array_column($products, 'unit-price'));

        $cheapestProduct = array_filter(
            $products, function ($product) use ($cheapestPrice) {
            return $product->{'unit-price'} === $cheapestPrice;
        }
        );

        return array_pop($cheapestProduct);
    }

    private function getFreeProduct(int $categoryId): array
    {
        return [
            'quantity' => 1,
            'unit-price' => 'FREE',
            'category' => $categoryId
        ];

    }
}