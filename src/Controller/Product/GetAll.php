<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetAll extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $page = $request->getQueryParam('page', null);
        $perPage = $request->getQueryParam('perPage', null);
        $id = $request->getQueryParam('id', null);
        $description = $request->getQueryParam('description', null);
        $category = $request->getQueryParam('category', null);
        $price = $request->getQueryParam('price', null);

        $products = $this->getProductService()->getProductsByPage(
            (int) $page,
            (int) $perPage,
            $id,
            $description,
            $category,
            $price
        );

        return $this->jsonResponse($response, 'success', $products, 200);
    }
}
