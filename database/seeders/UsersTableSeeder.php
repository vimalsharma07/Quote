<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'phone' => '7088434331',
            'password' => bcrypt('user@123'),
        ]);
    }
}
