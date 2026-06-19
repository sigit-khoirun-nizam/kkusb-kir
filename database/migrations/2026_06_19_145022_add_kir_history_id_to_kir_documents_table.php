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
        Schema::table('kir_documents', function (Blueprint $table) {
            $table->foreignId('kir_history_id')->nullable()->after('kendaraan_id')->constrained('kir_histories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kir_documents', function (Blueprint $table) {
            $table->dropForeign(['kir_history_id']);
            $table->dropColumn('kir_history_id');
        });
    }
};
