<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->insert([
            ['status' => 'Chờ xử lý'],
            ['status' => 'Đã tiếp nhận'],
            ['status' => 'Đang giao hàng'],
            ['status' => 'Giao hàng thành công'],
            ['status' => 'Đã hủy'],
            ['status' => 'Yêu cầu hoàn trả'],
            ['status' => 'Xác nhận hoàn trả'],
            ['status' => 'Hoàn trả'],
            ['status' => 'Hoàn trả thành công'],
        ]);
    }
}
