<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([ 'name' => 'super admin', 'guard_name' => 'api' ]);
        Role::create([ 'name' => 'admin', 'guard_name' => 'web' ]);
        Role::create([ 'name' => 'admin', 'guard_name' => 'api' ]);
        Role::create([ 'name' => 'distributor', 'guard_name' => 'api' ]);
        Role::create([ 'name' => 'reseller', 'guard_name' => 'api' ]);
        Role::create([ 'name' => 'member', 'guard_name' => 'api' ]);
        Role::create([ 'name' => 'customer', 'guard_name' => 'web' ]);
        Role::create([ 'name' => 'customer', 'guard_name' => 'api' ]);
    }
}
