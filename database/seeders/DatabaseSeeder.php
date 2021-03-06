<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            VariantSeeder::class,
            CategorySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            OtherSettingSeeder::class,
            WebConfigSeeder::class,
            CourierSeeder::class,
        ]);
    }
}
