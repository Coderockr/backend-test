<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('investment_date');
            $table->decimal('invested_amount', 8, 2)->unsigned();
            $table->decimal('expected_balance', 8, 2)->default(0);

            $table->enum('status', ['ACTIVE','WITHDRAWN'])->default('ACTIVE');

            $table->decimal('taxes',8,2)->default(0);
            $table->decimal('withdrawn_amount',8,2)->default(0);
            $table->date('withdrawal_date')->nullable();

            $table->foreignUuid('owner_id')
            ->references('id')
            ->on('owners')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
};
