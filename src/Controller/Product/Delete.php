<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;

final class Delete extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $productId = (string) $args['id'];
        $this->getProductService()->delete($productId);

        return $this->jsonResponse($response, 'success', null, 204);
    }
}
