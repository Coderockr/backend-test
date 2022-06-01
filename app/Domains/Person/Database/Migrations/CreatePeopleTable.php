<?php

namespace App\Domains\Person\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public.people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->default(true)->comment('registro ativo');
            $table->integer('type')->comment('tipo: 
            0 usuário; 
            1 associado; 
            ');
            $table->boolean('person')->comment('pessoa: 
            false fisica ; 
            true juridica');
            $table->string('name');
            $table->string('nickname')->unique()->nullable();
            $table->text('photo')->nullable()->comment('foto');
            $table->string('reason_social')->nullable()->comment('nome fantasia');
            $table->string('cpf_cnpj')->nullable()->unique();
            $table->date('date_birth')->nullable()->comment('data de nascimento');
            $table->integer('gender')->default(0)->comment('genero: 0 nao informar ; 1 masculino ; 2 feminino');
            $table->string('email')->nullable()->unique();
            $table->integer('address_id')->nullable()->comment('id do endereço');
            $table->string('name_dad')->nullable()->comment('nome do pai');
            $table->string('name_mother')->nullable()->comment('nome da mao');
            $table->string('api_report_id')->nullable()->comment('id relatorio api idwall');
            $table->date('last_query_tjms')->nullable()->comment('ultima consulta TJMS');
            $table->date('register_updated_at')->nullable()->comment('cadastro atualizado em');
            $table->text('note')->nullable()->comment('observaçoes');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('role_id')->nullable()->comment('id do cargo');
            
            $table->foreign('address_id')->references('id')->on('public.address');
            $table->foreign('role_id')->references('id')->on('public.roles');

            $table->rememberToken();
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
        DB::statement('DROP TABLE IF EXISTS public.people CASCADE');
    }
}