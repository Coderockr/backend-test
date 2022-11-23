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
        Schema::create('movement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->decimal('gain_real', 5, 2);
            $table->decimal('updated_value', 10,2);
            $table->dateTime('created_at');

            $table->foreign('investment_id')->references('id')->on('investment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movement');
    }
};
