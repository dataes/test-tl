<?php

declare(strict_types=1);

namespace Tests\integration;

class DiscountTest extends BaseTestCase
{
    private static int $id;

    /**
     * Test Get 10% A customer who has already bought for over € 1000
     */
    public function testGet10PercentsDiscount(): void
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
                        "quantity" => "1"
                    ],
                    [
                        "product-id" => "B103",
                        "quantity" => "1"
                    ]
                ],
                'total' => "1001"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('900.9', $decodedResult->message->total);
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('discountMessages', $result);
        $this->assertStringContainsString('You got 10% of discount because you already bought for over 1000', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);
    }

    /**
     * Test Do Not Get 10% A customer who has bought € 1000 or less
     */
    public function testDoNotGet10PercentsDiscount(): void
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
                        "quantity" => "1"
                    ],
                    [
                        "product-id" => "B103",
                        "quantity" => "1"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('1000', $decodedResult->message->total);
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('discountMessages', $result);
        $this->assertStringNotContainsString('You got 10% of discount because you already bought for over 1000', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);
    }

    /**
     * Test If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product
     */
    public function testGet20PercentsOnCheapestProduct(): void
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
                        "quantity" => "2"
                    ],
                    [
                        "product-id" => "B103",
                        "quantity" => "1"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('998.05', $decodedResult->message->total);
        $this->assertEquals('1.95', $decodedResult->message->products[0]->discount);
        $this->assertStringContainsString('discount', $result);
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('discountMessages', $result);
        $this->assertStringContainsString('You got 20% on cheapest product because you bought two or more products of category 1 (Tools)', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);

        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "A101",
                        "quantity" => "1"
                    ],
                    [
                        "product-id" => "A102",
                        "quantity" => "1"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('998.05', $decodedResult->message->total);
        $this->assertEquals('1.95', $decodedResult->message->products[0]->discount);
        $this->assertStringContainsString('discount', $result);
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('discountMessages', $result);
        $this->assertStringContainsString('You got 20% on cheapest product because you bought two or more products of category 1 (Tools)', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);
    }

    /**
     * Test If you buy less than 2 products of category "Tools" (id 1), you do not get a 20% discount on the cheapest
     * product
     */
    public function testDoNotGet20PercentsOnCheapestProduct(): void
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
                        "quantity" => "1"
                    ],
                    [
                        "product-id" => "B103",
                        "quantity" => "1"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string)$response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('1000', $decodedResult->message->total);
        $this->assertStringNotContainsString('discount', $result);
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('discountMessages', $result);
        $this->assertStringNotContainsString(
            'You got 20% on cheapest product because you bought two or more products of category 1 (Tools)', $result
        );
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);
    }

    /**
     * Test For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
     */
    public function testGetASixthForFree(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "B103",
                        "quantity" => "5"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('1000', $decodedResult->message->total);
        $this->assertStringContainsString('discount', $result);
        $this->assertStringContainsString('FREE', $result);
        $this->assertStringContainsString('discountMessages', $result);
        $this->assertStringContainsString('You got a free product of category 2 (Switches) because you bought five', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);

        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "B103",
                        "quantity" => "4"
                    ],
                    [
                        "product-id" => "B102",
                        "quantity" => "1"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('1000', $decodedResult->message->total);
        $this->assertStringContainsString('discount', $result);
        $this->assertStringContainsString('FREE', $result);
        $this->assertStringContainsString('discountMessages', $result);
        $this->assertStringContainsString('You got a free product of category 2 (Switches) because you bought five', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);
    }

    /**
     * Test For every product of category "Switches" (id 2), when you do not buy five, you do not get a sixth for free.
     */
    public function testNotGetASixthForFree(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "B103",
                        "quantity" => "6"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('1000', $decodedResult->message->total);
        $this->assertStringNotContainsString('discount', $result);
        $this->assertStringNotContainsString('FREE', $result);
        $this->assertStringNotContainsString('discountMessages', $result);
        $this->assertStringNotContainsString('You got a free product of category 2 (Switches) because you bought five', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);

        $response = $this->runApp(
            'POST',
            '/api/v1/orders',
            [
                'id' => '9999',
                'customer-id' => '1',
                'items' => [
                    [
                        "product-id" => "B103",
                        "quantity" => "4"
                    ],
                    [
                        "product-id" => "B102",
                        "quantity" => "4"
                    ]
                ],
                'total' => "1000"
            ]
        );

        $result = (string) $response->getBody();

        $decodedResult = json_decode($result);

        self::$id = $decodedResult->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('1000', $decodedResult->message->total);
        $this->assertStringNotContainsString('discount', $result);
        $this->assertStringNotContainsString('FREE', $result);
        $this->assertStringNotContainsString('discountMessages', $result);
        $this->assertStringNotContainsString('You got a free product of category 2 (Switches) because you bought five', $result);
        $this->assertStringNotContainsString('error', $result);
        $this->runApp('DELETE', '/api/v1/orders/' . self::$id);
    }
}
