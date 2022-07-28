<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            ['method' => 'Thanh toán khi nhận hàng'],
            ['method' => 'Thẻ tín dụng'],
            ['method' => 'Ví VNPay'],
            ['method' => 'Ví Zalo Pay'],
            ['method' => 'Momo'],
        ]);
    }
}
