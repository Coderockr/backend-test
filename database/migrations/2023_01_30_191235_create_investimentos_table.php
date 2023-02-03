<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investimentos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date("data");
            $table->integer("saldo_inicial");
            $table->integer("ganhos")->nullable();
            $table->foreignUuid('investidor_id')
                ->references('id')
                ->on('investidors')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->boolean('retirou')->default(false);
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
        Schema::dropIfExists('investimentos');
    }
}