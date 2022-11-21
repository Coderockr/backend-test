<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('description');
            $table->decimal('gain', 10, 2);
            $table->dateTime('created_at');
            $table->dateTime('withdrawn_at')->nullable()->default(NULL);
            $table->boolean('is_withdrawn')->default(0);
            $table->unsignedDecimal('initial_investment', 10, 2);
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('investments');
    }
};
