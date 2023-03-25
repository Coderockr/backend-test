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
        Schema::table('investimento', function (Blueprint $table) {
            $table->date('data_retirada')->nullable(true);
            $table->decimal('valor_retirada', unsigned: true)->nullable(true);
            $table->decimal('valor_imposto', unsigned: true)->nullable(true);
            $table->decimal('taxa_imposto', unsigned: true)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investimento', function (Blueprint $table) {
            $table->dropColumn('data_retirada');
            $table->dropColumn('valor_imposto');
            $table->dropColumn('taxa_imposto');
        });
    }
};
