<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'username' => 'admin',
                'password' => bcrypt('password'),
                'role' => 'ADMIN',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'asifyan@gmail.com'],
            [
                'username' => 'shifyannn',
                'password' => bcrypt('password'),
                'role' => 'USER',
            ]
        );

        \App\Models\User::firstOrCreate(
            ['email' => 'dev@gmail.com'],
            [
                'username' => 'dev',
                'password' => bcrypt('devPassword'),
                'role' => 'DEV',
            ]
        );

        $this->call([
            CategorySeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
