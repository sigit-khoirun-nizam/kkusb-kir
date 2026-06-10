<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'nomor_pintu',
        'nopol',
        'jenis',
        'deskripsi',
        'exp_kir',
        'biaya',
        'jasa',
        'total',
        'no_pr',
        'no_spk',
        'status',
    ];

    protected $casts = [
        'exp_kir' => 'date',
        'biaya' => 'decimal:2',
        'jasa' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function histories(): HasMany
    {
        return $this->hasMany(KirHistory::class, 'kendaraan_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(KirDocument::class, 'kendaraan_id');
    }

    // Accessors for formatted numbers
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

    // Status based on expiration date (Next Alert Date / Expiry logic)
    public function getStatusLabelAttribute(): string
    {
        if (!$this->exp_kir) {
            return 'Belum KIR';
        }

        $daysRemaining = now()->startOfDay()->diffInDays($this->exp_kir, false);

        if ($daysRemaining < 0) {
            return 'Urgent (Expired)';
        } elseif ($daysRemaining <= 30) {
            return 'Urgent';
        } elseif ($daysRemaining <= 60) {
            return 'Warning';
        }

        return 'Aman';
    }

    public function getStatusColorAttribute(): string
    {
        if (!$this->exp_kir) {
            return 'gray';
        }

        $daysRemaining = now()->startOfDay()->diffInDays($this->exp_kir, false);

        if ($daysRemaining < 0) {
            return 'red'; // Urgent / Expired
        } elseif ($daysRemaining <= 30) {
            return 'red'; // Urgent
        } elseif ($daysRemaining <= 60) {
            return 'yellow'; // Warning
        }

        return 'green'; // Aman
    }
}
