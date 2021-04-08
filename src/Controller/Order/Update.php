<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Slim\Http\Request;
use Slim\Http\Response;

final class Update extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = (array) $request->getParsedBody();
        $order = $this->getOrderService()->update($input, (int) $args['id']);

        return $this->jsonResponse($response, 'success', $order, 200);
    }
}
