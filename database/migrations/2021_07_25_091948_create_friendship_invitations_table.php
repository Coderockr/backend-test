<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendshipInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friendship_invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('who invited');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('guest_id')->unsigned()->comment('the guest');
            $table->foreign('guest_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('status', 20)->default('pending')->comment('pending, confirmed, rejected');
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
        Schema::dropIfExists('friendship_invitations');
    }
}
