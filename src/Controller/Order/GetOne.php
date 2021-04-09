<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetOne extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $orderId = (int) $args['id'];

        $order = $this->getOrderService()->getOne($orderId);

        return $this->jsonResponse($response, 'success', $order, 200);
    }
}
