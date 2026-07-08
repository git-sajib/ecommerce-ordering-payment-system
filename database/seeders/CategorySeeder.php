<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            'Electronics',

            'Fashion',

            'Books',

        ];

        foreach ($categories as $category) {

            Category::updateOrCreate(
                [
                    'slug' => Str::slug($category),
                ],
                [
                    'name' => $category,
                    'status' => true,
                ]
            );
        }
    }
}
