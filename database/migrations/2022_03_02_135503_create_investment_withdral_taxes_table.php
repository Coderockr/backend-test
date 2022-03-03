<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentWithdralTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_withdral_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->foreign('investment_id')->references('id')->on('investments');
            $table->float('less_than_one');
            $table->float('between_one_and_two');
            $table->float('older_than_two');
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
        Schema::dropIfExists('investment_withdral_taxes');
    }
}
