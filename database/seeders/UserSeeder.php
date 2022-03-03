<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@admin.com',
            'phone_number' => '0',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'type' => 'staff',
        ]);
        $super_admin->assignRole('super admin');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone_number' => '0',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'type' => 'staff',
        ]);
        $admin->assignRole('admin');
    }
}
