<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            ['type_name' => 'Whey Protein'],
            ['type_name' => 'BCAA'],
            ['type_name' => 'Casein Protein'],
            ['type_name' => 'Creatine'],
            ['type_name' => 'BETA-alanine / Carnosine'],
            ['type_name' => 'Oxide Nitric'],
            ['type_name' => 'Glutamine'],
            ['type_name' => 'ZMA'],
            ['type_name' => 'Carnitine'],
            ['type_name' => 'Beta Ecdysterone'],
        ]);
    }
}
