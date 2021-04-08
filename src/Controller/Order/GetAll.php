<?php

declare(strict_types=1);

namespace App\Controller\Order;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetAll extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $input = (array) $request->getParsedBody();
        $userId = $this->getAndValidateUserId($input);
        $page = $request->getQueryParam('page', null);
        $perPage = $request->getQueryParam('perPage', null);
        $total = $request->getQueryParam('total', null);

        $orders = $this->getOrderService()->getOrdersByPage(
            $userId,
            (int) $page,
            (int) $perPage,
            $total
        );

        return $this->jsonResponse($response, 'success', $orders, 200);
    }
}
