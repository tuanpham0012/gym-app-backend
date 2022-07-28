<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shipping_methods')->insert([
            [
                'shipping_unit' => 'Giao Hàng Tiết Kiệm',
                'transport_fee' => '50000',
            ],
            [
                'shipping_unit' => 'Giao Hàng Nhanh',
                'transport_fee' => '50000',
            ],
            [
                'shipping_unit' => 'Viettel Post',
                'transport_fee' => '50000',
            ],
            [
                'shipping_unit' => 'J&T Express',
                'transport_fee' => '50000',
            ],
            [
                'shipping_unit' => 'GrabExpress',
                'transport_fee' => '50000',
            ],
            [
                'shipping_unit' => 'Ninja Van',
                'transport_fee' => '50000',
            ],

        ]);
    }
}
