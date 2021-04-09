<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Slim\Http\Request;
use Slim\Http\Response;

final class Delete extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $orderId = (int) $args['id'];

        $this->getOrderService()->delete($orderId);

        return $this->jsonResponse($response, 'success', null, 204);
    }
}
