<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingAsset extends Model
{
    use HasFactory;
    protected $table    = 'shopping_assets';
    protected $fillable = [
        'asset_type_id',
        'job_id',
        'organization_id',
        'quantity_registered',
        'quantity_approved',
        'price',
        'receiving_time',
        'shopping_plan_organization_id',
        'shopping_plan_company_id',
        'year',
        'quarter',
        'month',
        'week',
        'description',
        'created_by',
    ];

    public const ACTION_NEW      = 1;
    public const ACTION_ROTATION = 2;

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'id');
    }
}
