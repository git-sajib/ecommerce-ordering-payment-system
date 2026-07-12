<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_products_endpoint_exists(): void
    {
        $response = $this->get('/api/v1/products');

        $this->assertNotEquals(404, $response->status());
    }
}
