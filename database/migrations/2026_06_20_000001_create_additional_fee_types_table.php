<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('additional_fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status', 20)->default('aktif'); // aktif / nonaktif
            $table->timestamps();
        });

        // Seed defaults
        $types = [
            'TIDAK SESUAI REAL FISIK',
            'STIKER PEMANTUL',
            'KELEBIHAN DIMENSI',
            'KARTU BLUE E BARU DARI KOTA ASAL',
            'ACC KERUSAKAN',
            'ACC KENDARAAN TIDAK DATANG',
        ];

        foreach ($types as $type) {
            DB::table('additional_fee_types')->insert([
                'name' => $type,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_fee_types');
    }
};
