<?php

namespace App\Domains\System\Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public.address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->default(true)->comment('registro ativo');
            $table->string('cep')->nullable();
            $table->string('street')->nullable()->comment('nome da rua');
            $table->string('number')->nullable()->comment('numero do local');
            $table->string('complement')->nullable()->comment('complemento');
            $table->string('district')->nullable()->comment('nome do bairro');
            $table->integer('city')->nullable()->comment('codigo da cidade no ibge');
            $table->integer('uf')->nullable()->comment('codigo do estado no ibge');

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
        DB::statement('DROP TABLE IF EXISTS public.address CASCADE');
    }
}
