<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->integer('user_id')->comment('Người đặt hàng');
            $table->integer('admin_id')->comment('Người duyệt đơn')->nullable();
            $table->string('receiver')->comment('Người nhận');
            $table->string('receiver_phone')->comment('SDT người nhận');
            $table->string('address');
            $table->integer('total');
            $table->integer('order_status_id');
            $table->integer('payment_method_id');
            $table->string('shipping_method')->nullable();
            $table->integer('transport_fee')->unsigned()->default(0);
            $table->string('shipping_code')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
