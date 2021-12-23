<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oval = Category::create([
            'category_name' => 'Oval',
            'category_slug' => 'oval'
        ]);

        $oval->sub_category()->create([
            'sub_category_name' => 'Gamis',
            'sub_category_slug' => 'gamis'
        ]);
    }
}
