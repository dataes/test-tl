<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Slim\Http\Request;
use Slim\Http\Response;

final class Delete extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = (array) $request->getParsedBody();
        $orderId = (int) $args['id'];
        $userId = $this->getAndValidateUserId($input);

        $this->getOrderService()->delete($orderId, $userId);

        return $this->jsonResponse($response, 'success', null, 204);
    }
}
