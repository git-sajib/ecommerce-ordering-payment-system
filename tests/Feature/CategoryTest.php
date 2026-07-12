<?php

namespace Tests\Feature;

use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_category_tree_endpoint_is_accessible(): void
    {
        $this->getJson('/api/v1/categories/tree')
            ->assertOk();
    }

    public function test_category_dfs_endpoint_is_accessible(): void
    {
        $this->getJson('/api/v1/categories/dfs')
            ->assertOk();
    }
}
