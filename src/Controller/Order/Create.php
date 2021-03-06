<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Slim\Http\Request;
use Slim\Http\Response;

final class Create extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $input = (array)$request->getParsedBody();

        // check if user provided exist for the order
        $this->getFindUserService()->getOne((int)$input['customer-id']);

        // check if products exists
        if (isset($input['items'])) {
            foreach ($input['items'] as $key => $product) {
                // note : I assume that the products are in the DB so only product-id data is useful
                $products[] = $this->getProductService()->getOne($product['product-id']);
                // todo add quantity as a property in Product + logic to subtract quantity on order (inventory purpose)
                $products[$key]->quantity = (int)$product['quantity'];
            }
            $input['items'] = $products;
        }

        $order = $this->getOrderService()->create($input);

        return $this->jsonResponse($response, 'success', $this->getDiscountService()->getDiscounts($order), 201);
    }
}
