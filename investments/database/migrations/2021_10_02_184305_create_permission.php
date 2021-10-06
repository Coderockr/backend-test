<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigInteger('users_id')->unsigned();
            $table->bigInteger('roles_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('permissions', function ($table) {
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('permissions', function ($table) {
            $table->foreign('roles_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::table('permissions', function ($table) {
            $table->primary(['users_id', 'roles_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
