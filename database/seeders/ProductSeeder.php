<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['sku' => 'IP16PRO001'],
            [
                'category_id' => 1,
                'name' => 'iPhone 16 Pro',
                'description' => 'Apple flagship smartphone',
                'price' => 159999.99,
                'stock' => 25,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'S25U001'],
            [
                'category_id' => 1,
                'name' => 'Galaxy S25 Ultra',
                'description' => 'Samsung flagship smartphone',
                'price' => 149999.99,
                'stock' => 30,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'MBA001'],
            [
                'category_id' => 1,
                'name' => 'MacBook Air M4',
                'description' => 'Apple laptop',
                'price' => 189999.99,
                'stock' => 15,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'TSHIRT001'],
            [
                'category_id' => 2,
                'name' => 'Premium T-Shirt',
                'description' => '100% Cotton',
                'price' => 999.99,
                'stock' => 100,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'JEANS001'],
            [
                'category_id' => 2,
                'name' => 'Blue Jeans',
                'description' => 'Slim Fit',
                'price' => 2499.99,
                'stock' => 60,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'SHOE001'],
            [
                'category_id' => 2,
                'name' => 'Running Shoes',
                'description' => 'Comfort Running Shoes',
                'price' => 4999.99,
                'stock' => 40,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'BOOK001'],
            [
                'category_id' => 3,
                'name' => 'Clean Code',
                'description' => 'Robert C. Martin',
                'price' => 850,
                'stock' => 50,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'BOOK002'],
            [
                'category_id' => 3,
                'name' => 'Design Patterns',
                'description' => 'GoF',
                'price' => 1200,
                'stock' => 35,
                'status' => true,
            ]
        );

        Product::updateOrCreate(
            ['sku' => 'BOOK003'],
            [
                'category_id' => 3,
                'name' => 'Laravel Up & Running',
                'description' => 'Laravel Development',
                'price' => 1500,
                'stock' => 20,
                'status' => true,
            ]
        );
    }
}
