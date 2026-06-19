<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kir_history_additional_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kir_history_id')->constrained('kir_histories')->cascadeOnDelete();
            $table->foreignId('additional_fee_type_id')->constrained('additional_fee_types')->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kir_history_additional_fees');
    }
};
