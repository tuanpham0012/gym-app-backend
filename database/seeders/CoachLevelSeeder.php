<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoachLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coach_levels')->insert([
            ['level' => 'HLV cao cấp / Senior Coach'],
            ['level' => 'HLV / Junior coach'],
            ['level' => 'HLV cao cấp / Transformation Expert'],
            ['level' => 'Quản lý HLV / Coach Manager'],
            ['level' => 'HLV cao cấp / Transformation Expert'],
        ]);
    }
}
