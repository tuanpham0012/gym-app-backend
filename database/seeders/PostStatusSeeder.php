<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_statuses')->insert([
            ['status' => 'Chờ duyệt'],
            ['status' => 'Đã duyệt'],
            ['status' => 'Đã ẩn'],
            ['status' => 'Đã xóa'],
        ]);
    }
}
