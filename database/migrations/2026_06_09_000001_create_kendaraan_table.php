<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pintu', 50)->nullable();
            $table->string('nopol', 50)->nullable();
            $table->string('jenis', 50)->nullable();
            $table->string('deskripsi', 150)->nullable();
            $table->date('exp_kir')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->decimal('jasa', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('no_pr', 100)->nullable();
            $table->string('no_spk', 100)->nullable();
            $table->string('status', 50)->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
