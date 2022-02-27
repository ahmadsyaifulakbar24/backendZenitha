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
        DB::statement("
            INSERT INTO `provinces` (`id`, `province`) VALUES
            ('11', 'ACEH'),
            ('12', 'SUMATERA UTARA'),
            ('13', 'SUMATERA BARAT'),
            ('14', 'RIAU'),
            ('15', 'JAMBI'),
            ('16', 'SUMATERA SELATAN'),
            ('17', 'BENGKULU'),
            ('18', 'LAMPUNG'),
            ('19', 'KEPULAUAN BANGKA BELITUNG'),
            ('21', 'KEPULAUAN RIAU'),
            ('31', 'DKI JAKARTA'),
            ('32', 'JAWA BARAT'),
            ('33', 'JAWA TENGAH'),
            ('34', 'DI YOGYAKARTA'),
            ('35', 'JAWA TIMUR'),
            ('36', 'BANTEN'),
            ('51', 'BALI'),
            ('52', 'NUSA TENGGARA BARAT'),
            ('53', 'NUSA TENGGARA TIMUR'),
            ('61', 'KALIMANTAN BARAT'),
            ('62', 'KALIMANTAN TENGAH'),
            ('63', 'KALIMANTAN SELATAN'),
            ('64', 'KALIMANTAN TIMUR'),
            ('65', 'KALIMANTAN UTARA'),
            ('71', 'SULAWESI UTARA'),
            ('72', 'SULAWESI TENGAH'),
            ('73', 'SULAWESI SELATAN'),
            ('74', 'SULAWESI TENGGARA'),
            ('75', 'GORONTALO'),
            ('76', 'SULAWESI BARAT'),
            ('81', 'MALUKU'),
            ('82', 'MALUKU UTARA'),
            ('91', 'PAPUA BARAT'),
            ('94', 'PAPUA')
        ");
    }
}
