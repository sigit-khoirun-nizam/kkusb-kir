<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KirDocument extends Model
{
    use HasFactory;

    protected $table = 'kir_documents';

    protected $fillable = [
        'kendaraan_id',
        'nama_file',
        'path',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}
