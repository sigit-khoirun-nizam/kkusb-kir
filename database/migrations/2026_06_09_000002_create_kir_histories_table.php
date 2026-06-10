<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kir_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->date('exp_kir_lama')->nullable();
            $table->date('exp_kir_baru')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->decimal('jasa', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('no_pr', 100)->nullable();
            $table->string('no_spk', 100)->nullable();
            $table->date('tanggal_proses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kir_histories');
    }
};
