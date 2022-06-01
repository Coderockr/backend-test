<?php

namespace App\Domains\Person\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public.phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->default(true)->comment('registro ativo');
            $table->integer('type')->comment('tipo: 0 fixo ; 1 celular ; 2 whatsapp');
            $table->string('number')->comment('numero do telefone');
            $table->integer('person_id')->nullable()->comment('id da pessoa');

            $table->foreign('person_id')->references('id')->on('public.people');
            
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
        DB::statement('DROP TABLE IF EXISTS public.phones CASCADE');
    }
}