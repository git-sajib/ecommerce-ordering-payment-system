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
                'email' => 'john@example.com',
            ],
            [
                'name' => 'John Doe',
                'password' => Hash::make('Password@123'),
                'role' => UserRole::CUSTOMER->value,
            ]
        );
    }
}
