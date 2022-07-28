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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->integer('coach_level_id');
            $table->text('experience')->nullable();
            $table->text('introduction')->nullable();
            $table->string('phone');
            $table->string('timeline')->nullable();
            $table->text('character')->nullable();
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->boolean('deleted')->default(0);
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
        Schema::dropIfExists('coaches');
    }
};
