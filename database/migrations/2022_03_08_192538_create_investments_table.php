<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsTable extends Migration
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
            $table->foreignId('investor_id');
            $table->decimal('amount_start', 12, 2);
            $table->date('date_creation');
            $table->boolean('withdrawn')->default(false);
            $table->date('date_withdraw')->nullable();
            $table->decimal('gain', 12, 2)->nullable();
            $table->decimal('tax', 12, 2)->nullable();
            $table->decimal('amount_total', 12, 2)->nullable();
            $table->decimal('amount_withdrawn', 12, 2)->nullable();
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
        Schema::dropIfExists('investments');
    }
}
