<?php

namespace Database\Seeders;

use App\Models\WebConfig;
use Illuminate\Database\Seeder;

class WebConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebConfig::create([
            'name' => 'reset_password_url',
            'value' => 'https://zenitha.com/reset_password'
        ]);
    }
}
