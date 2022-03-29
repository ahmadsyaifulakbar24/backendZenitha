<?php

namespace Database\Seeders;

use App\Models\OtherSetting;
use App\Models\WebSetting;
use Illuminate\Database\Seeder;

class OtherSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OtherSetting::create([
            'category' => 'footer_banner',
            'content' => 'image.png',
            'type' => 'image',
            'order' => 1
        ]);

        OtherSetting::create([
            'category' => 'second_banner',
            'content' => 'image.png',
            'type' => 'image',
            'order' => 1
        ]);
        
        OtherSetting::create([
            'category' => 'second_banner',
            'content' => 'image.png',
            'type' => 'image',
            'order' => 2
        ]);

        WebSetting::create([
            'logo' => 'image.png',
            'name' => 'website name',
            'email' => 'email website',
            'phone' => 9876537617,
            'province_id'  => 6,
            'city_id' => 153,
            'district_id' => 2103,
            'postal_code' => 16920,
            'fb_status' => 0,
            'tw_status' => 0,
            'yt_status' => 0,
            'ig_status' => 0,
        ]);
    }
}
