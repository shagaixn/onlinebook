<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@example2.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin_password11'),
                'role' => 'admin'
            ]
        );
    }
}