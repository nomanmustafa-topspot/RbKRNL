<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create an admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'support@speakeasymarketinginc.com',
            'password' => Hash::make('support@123'),
            'type' => 'admin',
        ]);

        // Create a normal user
        $normalUser = User::create([
            'name' => 'User',
            'email' => 'user@speakeasymarketinginc.com',
            'password' => Hash::make('user@123'),
            'type' => 'user',
        ]);
        // Assign roles to users
        $adminUser->assignRole($adminRole);
        $normalUser->assignRole($userRole);
    }
}
