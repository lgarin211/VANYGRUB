<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'VANY GROUB Admin',
            'email' => 'admin@VANY GROUB.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        echo "Admin user created successfully!\n";
        echo "Email: admin@VANY GROUB.com\n";
        echo "Password: password123\n";
    }
}
