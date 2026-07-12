<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'assessment@example.com',
            ],
            [
                'name' => 'Assessment User',
                'password' => Hash::make('password123'),
                'role' => UserRole::CUSTOMER->value,
            ]
        );
    }
}
