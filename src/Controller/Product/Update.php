<?php

declare(strict_types=1);

namespace App\Controller\Product;

use Slim\Http\Request;
use Slim\Http\Response;

final class Update extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = (array) $request->getParsedBody();
        $task = $this->getProductService()->update($input, (string) $args['id']);

        return $this->jsonResponse($response, 'success', $task, 200);
    }
}
