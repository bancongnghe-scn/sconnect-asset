<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssetHistory extends Model
{
    use HasFactory;
    protected $table = 'asset_histories';

    protected $fillable = [
        'asset_id',
        'action',
        'date',
        'description',
        'price',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assetRepair(): HasOne
    {
        return $this->hasOne(AssetRepair::class, 'asset_history_id', 'id');
    }

    public function createBy(): HasOne
    {
        return $this->hasOne(user::class, 'id', 'created_by');
    }
}
