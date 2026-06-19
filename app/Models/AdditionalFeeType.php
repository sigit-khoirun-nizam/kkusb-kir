<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdditionalFeeType extends Model
{
    use HasFactory;

    protected $table = 'additional_fee_types';

    protected $fillable = [
        'name',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function historyFees(): HasMany
    {
        return $this->hasMany(KirHistoryAdditionalFee::class, 'additional_fee_type_id');
    }
}
