<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_orders(): void
    {
        $this->getJson('/api/v1/orders')
            ->assertUnauthorized();
    }
}
