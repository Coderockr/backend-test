<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('investment_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->string('description');
            $table->decimal('value', 10, 2);
            $table->dateTime('movement_at');

            $table->foreign('investment_id')->references('id')->on('investments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('investment_movements');
    }
};
