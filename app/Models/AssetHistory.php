<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHistory extends Model
{
    use HasFactory;
    protected $table = 'asset_histories';

    protected $fillable = [
        'asset_id',
        'action',
        'date',
        'description',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
