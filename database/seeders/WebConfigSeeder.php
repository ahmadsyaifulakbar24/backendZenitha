<?php

namespace Database\Seeders;

use App\Models\ShippingSetting;
use App\Models\WebConfig;
use Carbon\Carbon;
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

        ShippingSetting::create([
            'minimum_price' => 0,
            'max_shipping_discount' => 0,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()
        ]);
    }
}
