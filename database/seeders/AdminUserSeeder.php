<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'nombre' => 'admin',
            'email' => 'quedadappss@gmail.com',
            'password' => Hash::make('admin11(12)'),
            'role' => 'admin',
        ]);

        User::create([
            'nombre' => 'ivanhorcaga17',
            'email' => 'ivanhorcaga17@gmail.com',
            'password' => Hash::make('Prueba17(12)'),
            'role' => 'user',
        ]);
    }
}
