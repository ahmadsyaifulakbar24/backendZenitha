<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Courier::create([
            'courier' => 'POS Indonesia (POS)',
            'slug' => 'pos',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Lion Parcel (LION)',
            'slug' => 'lion',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Ninja Xpress (NINJA)',
            'slug' => 'ninja',
            'active' => 1
        ]);
        
        Courier::create([
            'courier' => 'ID Express (IDE)',
            'slug' => 'ide',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'SiCepat Express (SICEPAT)',
            'slug' => 'sicepat',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'SAP Express (SAP)',
            'slug' => 'sap',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Nusantara Card Semesta (NCS)',
            'slug' => 'ncs',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'AnterAja (ANTERAJA)',
            'slug' => 'anteraja',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Royal Express Indonesia (REX)',
            'slug' => 'rex',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Sentral Cargo (SENTRAL)',
            'slug' => 'sentral',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Wahana Prestasi Logistik (WAHANA)',
            'slug' => 'wahana',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'J&T Express (J&T)',
            'slug' => 'j&t',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'JET Express (JET)',
            'slug' => 'jet',
            'active' => 1
        ]);

        Courier::create([
            'courier' => '21 Express (DSE)',
            'slug' => 'des',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'First Logistics (FIRST)',
            'slug' => 'first',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'IDL Cargo (IDL)',
            'slug' => 'idl',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Jalur Nugraha Ekakurir (JNE)',
            'slug' => 'jne',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Citra Van Titipan Kilat (TIKI)',
            'slug' => 'tiki',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'RPX Holding (RPX)',
            'slug' => 'rpx',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Pandu Logistics (PANDU)',
            'slug' => 'pandu',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Pahala Kencana Express (PAHALA)',
            'slug' => 'pahala',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Solusi Ekspres (SLIS)',
            'slug' => 'slis',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Expedito* (EXPEDITO)',
            'slug' => 'expendito',
            'active' => 1
        ]);

        Courier::create([
            'courier' => 'Star Cargo (STAR)',
            'slug' => 'star',
            'active' => 1
        ]);
    }
}
