<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RendimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rendimento', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('investimento_id');
            $table->date('data');
            $table->double('valor_rendimento');

            $table->foreign('investimento_id')->references('id')->on('investimento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
