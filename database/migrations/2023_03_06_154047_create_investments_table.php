<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('status', ['ACTIVE', 'WITHDRAWN']);
            $table->date('create_date');
            $table->date('last_applied_rate');
            $table->integer('balance');
            $table->integer('investment_balance');
            $table->integer('expected_balance');
            $table->string('currency');

            $table->foreignId('investor_id')->references('id')->on('investors');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
