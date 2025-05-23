<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\RoleEnum;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create users
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password123'),
        ]);

        $admin->assignRole(RoleEnum::ADMIN->value);

        $operator = User::firstOrCreate([
            'email' => 'operator@example.com',
        ], [
            'name' => 'Operator User',
            'password' => Hash::make('password123'),
        ]);

        $operator->assignRole(RoleEnum::OPERATOR->value);
    }
}
