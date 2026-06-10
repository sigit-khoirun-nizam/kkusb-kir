<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KirHistory extends Model
{
    use HasFactory;

    protected $table = 'kir_histories';

    protected $fillable = [
        'kendaraan_id',
        'exp_kir_lama',
        'exp_kir_baru',
        'biaya',
        'jasa',
        'total',
        'no_pr',
        'no_spk',
        'tanggal_proses',
    ];

    protected $casts = [
        'exp_kir_lama' => 'date',
        'exp_kir_baru' => 'date',
        'tanggal_proses' => 'date',
        'biaya' => 'decimal:2',
        'jasa' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function getFormattedBiayaAttribute(): string
    {
        return number_format($this->biaya, 0, ',', '.');
    }

    public function getFormattedJasaAttribute(): string
    {
        return number_format($this->jasa, 0, ',', '.');
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', '.');
    }
}
