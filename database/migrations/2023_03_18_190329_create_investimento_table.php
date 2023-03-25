<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investimento', function (Blueprint $table) {
            $table->id();
            $table->string('investimento')->nullable(false);
            $table->decimal('valor_inicial', unsigned: true)->nullable(false);
            $table->decimal('valor_final', unsigned: true)->nullable(true);
            $table->date('data_criacao')->nullable(false);

            $table->foreignId('investidor_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investimento');
    }
};
