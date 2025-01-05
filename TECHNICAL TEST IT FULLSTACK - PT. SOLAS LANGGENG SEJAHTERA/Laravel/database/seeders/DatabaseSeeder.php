<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'nama'      => 'admin',
            'email'     => 'admin@mail.com',
            'role'      => 'admin',
            'username'  => 'admin',
            'password'  => bcrypt('admin123')
        ]);
        User::create([
            'nama'      => 'user',
            'email'     => 'user@mail.com',
            'role'      => 'user',
            'username'  => 'user',
            'password'  => bcrypt('user123')
        ]);
    }
}
