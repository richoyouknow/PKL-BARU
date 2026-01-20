<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create(['name' => 'admin', 'email' => 'admin@gmail.com', 'status' => 'active', 'role' => 'admin', 'password' => 'admin123']);
        User::create(['name' => 'anggota', 'email' => 'anggota@gmail.com', 'status' => 'active', 'role' => 'anggota', 'password' => 'anggota']);
        
    }
}
