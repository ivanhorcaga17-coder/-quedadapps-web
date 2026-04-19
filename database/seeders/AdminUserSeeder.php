<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'quedadappss@gmail.com'],
            [
                'nombre' => 'admin',
                'password' => Hash::make('Admin11(12)'),
                'role' => 'admin',
            ]
        );
    }
}
