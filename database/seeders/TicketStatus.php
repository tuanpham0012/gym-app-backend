<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_statuses')->insert([
            ['status' => 'Chờ xác nhận'],
            ['status' => 'Đã kích hoạt'],
            ['status' => 'Đã hết hạn'],
            ['status' => 'Đã khóa'],
        ]);
    }
}
