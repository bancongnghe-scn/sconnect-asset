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

    public const ACTION_NEW                         = 1;
    public const ACTION_ROTATION                    = 2;
    public const STATUS_PENDING_HR_MANAGER_APPROVAL = 1;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL = 2;
    public const STATUS_PENDING_GENERAL_APPROVAL    = 3;
    public const STATUS_HR_MANAGER_APPROVAL         = 4;
    public const STATUS_HR_MANAGER_DISAPPROVAL      = 5;
    public const STATUS_ACCOUNTANT_APPROVAL         = 6;
    public const STATUS_ACCOUNTANT_DISAPPROVAL      = 7;
    public const STATUS_GENERAL_APPROVAL            = 8;
    public const STATUS_GENERAL_DISAPPROVAL         = 9;
    public const PRICE_HR_APPROVAL                  = 10000000; // 10tr
    public const PRICE_ACCOUNTANT_APPROVAL          = 50000000; // 50tr

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'id');
    }
}
