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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->double('initial_amount', 14, 4);
            $table->double('gains', 14, 4)->nullable();
            $table->double('taxes', 14, 4)->nullable();
            $table->double('final_amount',16,4)->nullable();
            $table->date('creation_date');
            $table->date('withdrawal_date')->nullable();
            $table->boolean('withdrawal_done')->nullable();
            $table->foreignId('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
};
