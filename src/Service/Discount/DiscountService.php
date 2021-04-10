<?php

declare(strict_types=1);

namespace App\Service\Discount;

use App\Entity\Order;
use App\Repository\DiscountRepository;

final class DiscountService
{
    // todo create discountRepositoryInterface
    protected DiscountRepository $discountRepository;

    public function __construct(
        DiscountRepository $discountRepository
    ) {
        $this->discountRepository = $discountRepository;
    }

    public function getDiscount(object $order) : ?object {

//        - A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
//        - For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
//        - If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.

        $order->discountMessage = 'blabla';
        return $order;
    }
}