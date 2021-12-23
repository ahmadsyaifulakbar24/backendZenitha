<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert([
            'id' => 11,
            'province' => 'Aceh'
        ]);

        DB::table('provinces')->insert([
            'id' => 12,
            'province' => 'Sumatera Utara'
        ]);

        DB::table('provinces')->insert([
            'id' => 13,
            'province' => 'Sumatera Barat'
        ]);

        DB::table('provinces')->insert([
            'id' => 14,
            'province' => 'Riau'
        ]);

        DB::table('provinces')->insert([
            'id' => 15,
            'province' => 'Jambi'
        ]);

        DB::table('provinces')->insert([
            'id' => 16,
            'province' => 'Sumatera Selatan'
        ]);

        DB::table('provinces')->insert([
            'id' => 17,
            'province' => 'Bengkulu'
        ]);

        DB::table('provinces')->insert([
            'id' => 18,
            'province' => 'Lampung'
        ]);

        DB::table('provinces')->insert([
            'id' => 19,
            'province' => 'Bangka Belitung'
        ]);

        DB::table('provinces')->insert([
            'id' => 21,
            'province' => 'Kepulauan Riau'
        ]);

        DB::table('provinces')->insert([
            'id' => 31,
            'province' => 'D.K.I. Jakarta'
        ]);

        DB::table('provinces')->insert([
            'id' => 32,
            'province' => 'Jawa Barat'
        ]);

        DB::table('provinces')->insert([
            'id' => 33,
            'province' => 'Jawa Tengah'
        ]);

        DB::table('provinces')->insert([
            'id' => 34,
            'province' => 'D.I. Yogyakarta'
        ]);

        DB::table('provinces')->insert([
            'id' => 35,
            'province' => 'Jawa Timur'
        ]);

        DB::table('provinces')->insert([
            'id' => 36,
            'province' => 'Banten'
        ]);

        DB::table('provinces')->insert([
            'id' => 51,
            'province' => 'Bali'
        ]);

        DB::table('provinces')->insert([
            'id' => 52,
            'province' => 'Nusa Tenggara Barat'
        ]);

        DB::table('provinces')->insert([
            'id' => 53,
            'province' => 'Nusa Tenggara Timur'
        ]);

        DB::table('provinces')->insert([
            'id' => 61,
            'province' => 'Kalimantan Barat'
        ]);

        DB::table('provinces')->insert([
            'id' => 62,
            'province' => 'Kalimantan Tengah'
        ]);

        DB::table('provinces')->insert([
            'id' => 63,
            'province' => 'Kalimantan Selatan'
        ]);

        DB::table('provinces')->insert([
            'id' => 64,
            'province' => 'Kalimantan Timur'
        ]);

        DB::table('provinces')->insert([
            'id' => 65,
            'province' => 'Kalimantan Utara'
        ]);

        DB::table('provinces')->insert([
            'id' => 71,
            'province' => 'Sulawesi Utara'
        ]);

        DB::table('provinces')->insert([
            'id' => 72,
            'province' => 'Sulawesi Tengah'
        ]);

        DB::table('provinces')->insert([
            'id' => 73,
            'province' => 'Sulawesi Selatan'
        ]);

        DB::table('provinces')->insert([
            'id' => 74,
            'province' => 'Sulawesi Tenggara'
        ]);

        DB::table('provinces')->insert([
            'id' => 75,
            'province' => 'Gorontalo'
        ]);

        DB::table('provinces')->insert([
            'id' => 76,
            'province' => 'Sulawesi Barat'
        ]);

        DB::table('provinces')->insert([
            'id' => 81,
            'province' => 'Maluku'
        ]);

        DB::table('provinces')->insert([
            'id' => 82,
            'province' => 'Maluku Utara'
        ]);

        DB::table('provinces')->insert([
            'id' => 91,
            'province' => 'Papua Barat'
        ]);

        DB::table('provinces')->insert([
            'id' => 94,
            'province' => 'Papua'
        ]);
    }
}
