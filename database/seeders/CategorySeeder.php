<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Root Categories
        $electronics = Category::updateOrCreate(
            ['slug' => 'electronics'],
            [
                'name' => 'Electronics',
                'status' => true,
            ]
        );

        $fashion = Category::updateOrCreate(
            ['slug' => 'fashion'],
            [
                'name' => 'Fashion',
                'status' => true,
            ]
        );

        $books = Category::updateOrCreate(
            ['slug' => 'books'],
            [
                'name' => 'Books',
                'status' => true,
            ]
        );

        // Electronics Children
        Category::updateOrCreate(
            ['slug' => 'smartphones'],
            [
                'name' => 'Smartphones',
                'parent_id' => $electronics->id,
                'status' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'laptops'],
            [
                'name' => 'Laptops',
                'parent_id' => $electronics->id,
                'status' => true,
            ]
        );

        // Fashion Children
        Category::updateOrCreate(
            ['slug' => 'mens-fashion'],
            [
                'name' => "Men's Fashion",
                'parent_id' => $fashion->id,
                'status' => true,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'womens-fashion'],
            [
                'name' => "Women's Fashion",
                'parent_id' => $fashion->id,
                'status' => true,
            ]
        );
    }
}
