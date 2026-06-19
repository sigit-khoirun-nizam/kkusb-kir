<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KirHistoryAdditionalFee extends Model
{
    use HasFactory;

    protected $table = 'kir_history_additional_fees';

    protected $fillable = [
        'kir_history_id',
        'additional_fee_type_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function history(): BelongsTo
    {
        return $this->belongsTo(KirHistory::class, 'kir_history_id');
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(AdditionalFeeType::class, 'additional_fee_type_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', '.');
    }
}
