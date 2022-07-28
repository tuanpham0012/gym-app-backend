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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('member_code');
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('phone_number');
            $table->integer('cost')->default(0)->unsigned();
            $table->date('registration_date');
            $table->integer('duration')->comment('Đơn vị tính: Ngày');
            $table->integer('ticket_type_id')->comment('Loại vé tập');
            $table->integer('ticket_status_id')->comment('Trạng thái vé tập');
            $table->date('expiration_date');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
