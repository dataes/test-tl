<?php

declare(strict_types=1);

namespace Tests\integration;

class ProductTest extends BaseTestCase
{
    private static string $id;

    /**
     * Test Get All Products.
     */
    public function testGetProducts(): void
    {
        $response = $this->runApp('GET', '/api/v1/products');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('pagination', $result);
        $this->assertStringContainsString('data', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Products By Page.
     */
    public function testGetProductsByPage(): void
    {
        $response = $this->runApp('GET', '/api/v1/products?page=1&perPage=3');

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
     * Test Get One Product.
     */
    public function testGetProduct(): void
    {
        $response = $this->runApp('GET', '/api/v1/products/A101');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringContainsString('category', $result);
        $this->assertStringContainsString('price', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Product Not Found.
     */
    public function testGetProductNotFound(): void
    {
        $response = $this->runApp('GET', '/api/v1/products/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product.
     */
    public function testCreateProduct(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'NewProductTest', 'description' => 'My Desc.', 'category' => '1', 'price' => "99.99"]
        );

        $result = (string) $response->getBody();

        self::$id = json_decode($result)->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringContainsString('category', $result);
        $this->assertStringContainsString('price', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Product Created.
     */
    public function testGetProductCreated(): void
    {
        $response = $this->runApp('GET', '/api/v1/products/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringContainsString('category', $result);
        $this->assertStringContainsString('price', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Create Product With Empty category.
     */
    public function testCreateProductWithEmptyCategory(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'NewProduct2', 'description' => 'My Desc.', 'category' => '', 'price' => "99.99"]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product with category that does not exist.
     */
    public function testCreateProductWithUnknownCategory(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'NewProduct2', 'description' => 'My Desc.', 'category' => '80', 'price' => "99.99"]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product With Empty price.
     */
    public function testCreateProductWithEmptyPrice(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'New Product', 'description' => 'My Desc.', 'category' => '2', 'price' => '']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product With Empty id.
     */
    public function testCreateProductWithEmptyId(): void
    {
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => '', 'description' => 'My Desc.', 'category' => '1', 'price' => '99.99']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product Without Authorization Bearer JWT.
     */
    public function testCreateProductWithoutBearerJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = '';
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'test', 'description' => 'My Desc.', 'category' => '1', 'price' => '99.99']
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product With Invalid JWT.
     */
    public function testCreateProductWithInvalidJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'invalidToken';
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'test', 'description' => 'My Desc.', 'category' => '1', 'price' => '99.99']
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Product With Forbidden JWT.
     */
    public function testCreateProductWithForbiddenJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'Bearer eyJ0eXAiOiJK1NiJ9.eyJzdWIiOiI4Ii';
        $response = $this->runApp(
            'POST',
            '/api/v1/products',
            ['id' => 'test', 'description' => 'My Desc.', 'category' => '1', 'price' => '99.99']
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Product.
     */
    public function testUpdateProduct(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/products/' . self::$id,
            ['description' => 'My new Desc.', 'category' => '2', 'price' => '99.99']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('description', $result);
        $this->assertStringContainsString('category', $result);
        $this->assertStringContainsString('price', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Update product Without Send Data.
     */
    public function testUpdateProductWithoutSendData(): void
    {
        $response = $this->runApp('PUT', '/api/v1/products/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Product Not Found.
     */
    public function testUpdateProductNotFound(): void
    {
        $response = $this->runApp(
            'PUT',
            '/api/v1/products/123456789',
            ['description' => 'My new Desc.', 'category' => '2', 'price' => '99.99']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Delete Product.
     */
    public function testDeleteProduct(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/products/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Delete Product Not Found.
     */
    public function testDeleteProductNotFound(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/products/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }
}
