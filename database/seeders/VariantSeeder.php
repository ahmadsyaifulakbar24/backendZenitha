<?php

namespace Database\Seeders;

use App\Models\Variant;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warna = Variant::create([
            'id' => 1,
            'variant_name' => 'Warna',
            'image' => true,
        ]);
        $warna->variant_option()->createMany([
            [ 'variant_option_name' => 'Putih', 'default' => true ],
            [ 'variant_option_name' => 'Hitam', 'default' => true ],
            [ 'variant_option_name' => 'Biru', 'default' => true ],
            [ 'variant_option_name' => 'Biru Muda', 'default' => true ],
            [ 'variant_option_name' => 'Merah', 'default' => true ],
            [ 'variant_option_name' => 'Merah Muda', 'default' => true ],
            [ 'variant_option_name' => 'Orange', 'default' => true ],
            [ 'variant_option_name' => 'Kuning', 'default' => true ],
            [ 'variant_option_name' => 'Coklat', 'default' => true ],
            [ 'variant_option_name' => 'Hijau', 'default' => true ],
            [ 'variant_option_name' => 'Ungu', 'default' => true ],
            [ 'variant_option_name' => 'Abu', 'default' => true ],
        ]);

        $rasa = Variant::create([
            'id' => 2,
            'variant_name' => 'Rasa',
            'image' => false,
        ]);
        $rasa->variant_option()->createMany([
            [ 'variant_option_name' => 'Asin', 'default' => true],
            [ 'variant_option_name' => 'Manis', 'default' => true],
            [ 'variant_option_name' => 'Pedas', 'default' => true],
            [ 'variant_option_name' => 'Tawar', 'default' => true],
            [ 'variant_option_name' => 'Strawberi', 'default' => true],
            [ 'variant_option_name' => 'Vanila', 'default' => true],
            [ 'variant_option_name' => 'Coklat', 'default' => true],
            [ 'variant_option_name' => 'Honey', 'default' => true],
            [ 'variant_option_name' => 'Blueberry', 'default' => true],
        ]);

        $ukuran = Variant::create([
            'id' => 3,
            'variant_name' => 'Ukuran',
            'image' => false,
        ]);
        $ukuran->variant_option()->createMany([
            [ 'variant_option_name' => 2, 'default' => true],
            [ 'variant_option_name' => 4, 'default' => true],
            [ 'variant_option_name' => 6, 'default' => true],
            [ 'variant_option_name' => 8, 'default' => true],
            [ 'variant_option_name' => 10, 'default' => true],
            [ 'variant_option_name' => 12, 'default' => true],
            [ 'variant_option_name' => 14, 'default' => true],
            [ 'variant_option_name' => 16, 'default' => true],
            [ 'variant_option_name' => 'XS', 'default' => true],
            [ 'variant_option_name' => 'S', 'default' => true],
            [ 'variant_option_name' => 'M', 'default' => true],
            [ 'variant_option_name' => 'L', 'default' => true],
            [ 'variant_option_name' => 'XL', 'default' => true],
            [ 'variant_option_name' => 'XXL', 'default' => true],
        ]);

        $kemasan = Variant::create([
            'id' => 4,
            'variant_name' => 'Kemasan',
            'image' => false,
        ]);
        $kemasan->variant_option()->createMany([
            [ 'variant_option_name' => '2 pak', 'default' => true],
            [ 'variant_option_name' => '4 pak', 'default' => true],
            [ 'variant_option_name' => '6 pak', 'default' => true],
            [ 'variant_option_name' => '12 pak', 'default' => true],
            [ 'variant_option_name' => '1 pcs', 'default' => true],
            [ 'variant_option_name' => '2 pcs', 'default' => true],
            [ 'variant_option_name' => '6 pcs', 'default' => true],
            [ 'variant_option_name' => '12 pcs', 'default' => true],
            [ 'variant_option_name' => 'Softcover', 'default' => true],
            [ 'variant_option_name' => 'Hardcover', 'default' => true],
        ]);

        $ukuran_kemasan = Variant::create([
            'id' => 5,
            'variant_name' => 'Ukuran Kemasan',
            'image' => true,
        ]);
        $ukuran_kemasan->variant_option()->createMany([
            ['variant_option_name' => '25 gram', 'default' => true ],
            ['variant_option_name' => '40 gram', 'default' => true ],
            ['variant_option_name' => '100 gram', 'default' => true ],
            ['variant_option_name' => '160 gram', 'default' => true ],
            ['variant_option_name' => '190 gram', 'default' => true ],
            ['variant_option_name' => '225 gram', 'default' => true ],
            ['variant_option_name' => '300 gram', 'default' => true ],
            ['variant_option_name' => '400 gram', 'default' => true ],
            ['variant_option_name' => '500 gram', 'default' => true ],
            ['variant_option_name' => '1/4 kg', 'default' => true ],
            ['variant_option_name' => '1/2 kg', 'default' => true ],
            ['variant_option_name' => '3/4 kg', 'default' => true ],
            ['variant_option_name' => '1 kg', 'default' => true ],
            ['variant_option_name' => '2 kg', 'default' => true ],
            ['variant_option_name' => '2.5 kg', 'default' => true ],
            ['variant_option_name' => '3 kg', 'default' => true ],
            ['variant_option_name' => '4 kg', 'default' => true ],
            ['variant_option_name' => '5 kg', 'default' => true ],
            
            ['variant_option_name' => '45 ml', 'default' => true ],
            ['variant_option_name' => '150 ml', 'default' => true ],
            ['variant_option_name' => '200 ml', 'default' => true ],
            ['variant_option_name' => '320 ml', 'default' => true ],
            ['variant_option_name' => '400 ml', 'default' => true ],
            ['variant_option_name' => '550 ml', 'default' => true ],
            ['variant_option_name' => '670 ml', 'default' => true ],
            ['variant_option_name' => '720 ml', 'default' => true ],
            ['variant_option_name' => '1 l', 'default' => true ],
            ['variant_option_name' => '1.5 l', 'default' => true ],
            ['variant_option_name' => '3 l', 'default' => true ],
            ['variant_option_name' => '4 l', 'default' => true ],
            ['variant_option_name' => '5 l', 'default' => true ],
        ]);
    }
}
