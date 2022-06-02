<?php

namespace App\Domains\Investment\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment.moves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->default(true)->comment('registro ativo');
            $table->integer('type')->comment('tipo: 
            0 deposito; 
            1 saque;
            2 ganho;
            3 imposto;
            ');
            $table->string('value')->unique()->comment('valor');
            $table->integer('account_id')->comment('id da conta'); 
            
            $table->foreign('account_id')->references('id')->on('public.accounts');

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TABLE IF EXISTS investment.moves CASCADE');
    }
}