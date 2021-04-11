<?php

declare(strict_types=1);

namespace Tests\integration;

class OrderTest extends BaseTestCase
{
    private static int $id;

    /**
     * Test Get All Orders.
     */
    public function testGetOrders(): void
    {
        $response = $this->runApp('GET', '/api/v1/orders');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('pagination', $result);
        $this->assertStringContainsString('data', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Orders By Page.
     */
    public function testGetOrdersByPage(): void
    {
        $response = $this->runApp('GET', '/api/v1/orders?page=1&perPage=3');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('pagination', $result);
        $this->assertStringContainsString('data', $result);
        $this->assertStringContainsString('status', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get One Order.
     */
    public function testGetOrder(): void
    {
        $response = $this->runApp('GET', '/api/v1/orders/1000');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('total', $result);
        $this->assertStringContainsString('products', $result);
        $this->assertStringContainsString('userId', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Order Not Found.
     */
    public function testGetOrderNotFound(): void
    {
        $response = $this->runApp('GET', '/api/v1/orders/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Order.
     */
    public function testCreateOrder(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "A101",
                        "quantity" => "2",
                        "unit-price" => "9.75",
                        "total" => "19.50"
                    ],
                    [
                        "product-id" => "A102",
                        "quantity" => "2",
                        "unit-price" => "49.50",
                        "total" => "49.50"
                    ]
                ],
                'total' => "69.00"
            ]
        );

        $result = (string) $response->getBody();

        self::$id = json_decode($result)->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('total', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Order Created.
     */
    public function testGetOrderCreated(): void
    {
        $response = $this->runApp('GET', '/api/v1/orders/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('total', $result);
        $this->assertStringContainsString('total', $result);
        $this->assertStringContainsString('products', $result);
        $this->assertStringContainsString('userId', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Create Order With empty total.
     */
    public function testCreateOrderWithEmptyTotal(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '1000',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "A101",
                        "quantity" => "2",
                        "unit-price" => "9.75",
                        "total" => "19.50"
                    ],
                    [
                        "product-id" => "A102",
                        "quantity" => "2",
                        "unit-price" => "49.50",
                        "total" => "49.50"
                    ]
                ],
                'total' => ""
            ]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Order With empty customer id.
     */
    public function testCreateOrderWithEmptyCustomerId(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '',
                'items' => [
                    [
                        "product-id" => "A101",
                        "quantity" => "2",
                        "unit-price" => "9.75",
                        "total" => "19.50"
                    ],
                    [
                        "product-id" => "A102",
                        "quantity" => "2",
                        "unit-price" => "49.50",
                        "total" => "49.50"
                    ]
                ],
                'total' => "69.00"
            ]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Order With empty items.
     */
    public function testCreateOrderWithEmptyItems(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [],
                'total' => "69.00"
            ]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Order Without Authorization Bearer JWT.
     */
    public function testCreateOrderWithoutBearerJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = '';
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            ['name' => 'my Order', 'status' => 0]
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Order With Invalid JWT.
     */
    public function testCreateOrderWithInvalidJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'invalidToken';
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            ['name' => 'my orders', 'status' => 0]
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Order With Forbidden JWT.
     */
    public function testCreateOrderWithForbiddenJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'Bearer eyJ0eXAiOiJK1NiJ9.eyJzdWIiOiI4Ii';
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            ['name' => 'my orders', 'status' => 0]
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    // todo : update order

//    /**
//     * Test Update Order.
//     */
//    public function testUpdateOrder(): void
//    {
//        $response = $this->runApp(
//            'PUT',
//            '/api/v1/orders/' . self::$id,
//            ['total' => 9.09]
//        );
//
//        $result = (string) $response->getBody();
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
//        $this->assertStringContainsString('success', $result);
//        $this->assertStringContainsString('id', $result);
//        $this->assertStringContainsString('name', $result);
//        $this->assertStringContainsString('status', $result);
//        $this->assertStringNotContainsString('error', $result);
//    }
//
//    /**
//     * Test Update Task Without Send Data.
//     */
//    public function testUpdateOrderWithOutSendData(): void
//    {
//        $response = $this->runApp('PUT', '/api/v1/orders/' . self::$id);
//
//        $result = (string) $response->getBody();
//
//        $this->assertEquals(400, $response->getStatusCode());
//        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
//        $this->assertStringNotContainsString('success', $result);
//        $this->assertStringNotContainsString('id', $result);
//        $this->assertStringContainsString('error', $result);
//    }
//
//    /**
//     * Test Update Order Not Found.
//     */
//    public function testUpdateOrderNotFound(): void
//    {
//        $response = $this->runApp(
//            'PUT',
//            '/api/v1/orders/123456789',
//            ['id' => 'order1234']
//        );
//
//        $result = (string) $response->getBody();
//
//        $this->assertEquals(404, $response->getStatusCode());
//        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
//        $this->assertStringNotContainsString('success', $result);
//        $this->assertStringNotContainsString('id', $result);
//        $this->assertStringContainsString('error', $result);
//    }
//
//    /**
//     * Test Update Order of Another User.
//     */
//    public function testUpdateOrderOfAnotherUser(): void
//    {
//        $response = $this->runApp(
//            'PUT',
//            '/api/v1/orders/1',
//            ['total' => 9.09]
//        );
//
//        $result = (string) $response->getBody();
//
//        $this->assertEquals(404, $response->getStatusCode());
//        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
//        $this->assertStringNotContainsString('success', $result);
//        $this->assertStringNotContainsString('id', $result);
//        $this->assertStringContainsString('error', $result);
//    }
//
    /**
     * Test Delete Order.
     */
    public function testDeleteOrder(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/orders/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Delete Order Not Found.
     */
    public function testDeleteOrderNotFound(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/orders/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }
}
