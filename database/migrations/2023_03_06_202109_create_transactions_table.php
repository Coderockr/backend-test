<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->enum('from', ['INVESTMENT', 'DEPOSIT']);
            $table->enum('type', ['ADD', 'WITHDRAWN']);
            $table->integer('actual_balance');
            $table->integer('final_balance');
            $table->integer('actual_investment_balance');
            $table->integer('final_investment_balance');
            $table->float('rate_applied');
            $table->string('currency');

            $table->foreignId('investment_id')->references('id')->on('investments');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
