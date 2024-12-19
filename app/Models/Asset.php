<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;
    protected $table = 'assets';

    protected $fillable = [
        'name',
        'asset_type_id',
        'code',
        'supplier_id',
        'price',
        'warranty_months',
        'depreciation_months',
        'recent_maintenance_date',
        'next_maintenance_date',
        'description',
        'user_id',
        'status',
        'image',
        'location',         //thêm
        'organization_id',
        'import_warehouse_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public const STATUS_ACTIVE                  = 1;
    public const STATUS_PENDING                 = 2;
    public const STATUS_NEW                     = 3;
    public const STATUS_LOST                    = 4;
    public const STATUS_CANCEL                  = 5;
    public const STATUS_PROPOSAL_LIQUIDATION    = 6;
    public const STATUS_IN_LIQUIDATION          = 7;
    public const STATUS_LIQUIDATED              = 8;
    public const STATUS_DAMAGED                 = 9;
    public const STATUS_REPAIR                  = 10;

    public const STATUS_NAME = [
        self::STATUS_ACTIVE                     => 'Hoạt động',
        self::STATUS_PENDING                    => 'Tạm dừng',
        self::STATUS_NEW                        => 'Mới',
        self::STATUS_LOST                       => 'Đã mất',
        self::STATUS_CANCEL                     => 'Đã hủy',
        self::STATUS_PROPOSAL_LIQUIDATION       => 'Đề nghị thanh lý',
        self::STATUS_IN_LIQUIDATION             => 'Đang thanh lý',
        self::STATUS_LIQUIDATED                 => 'Đã thanh lý',
        self::STATUS_DAMAGED                    => 'Hỏng',
        self::STATUS_REPAIR                     => 'Đang sửa chữa',
    ];

    public const LOCATION_HN_1                  = 1;
    public const LOCATION_HN_2                  = 2;
    public const LOCATION_HN_3                  = 3;
    public const LOCATION_HN_4                  = 4;
    public const LOCATION_HN_5                  = 5;
    public const LOCATION_HN_6                  = 6;
    public const LOCATION_HN_7                  = 7;
    public const LOCATION_HCM                   = 8;

    public const LOCATION_NAME = [
        self::LOCATION_HN_1                     => 'HN_Tầng 1',
        self::LOCATION_HN_2                     => 'HN_Tầng 2',
        self::LOCATION_HN_3                     => 'HN_Tầng 3',
        self::LOCATION_HN_4                     => 'HN_Tầng 4',
        self::LOCATION_HN_5                     => 'HN_Tầng 5',
        self::LOCATION_HN_6                     => 'HN_Tầng 6',
        self::LOCATION_HN_7                     => 'HN_Tầng 7',
        self::LOCATION_HCM                      => 'HCM',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assetRepair(): HasMany
    {
        return $this->hasMany(AssetRepair::class, 'asset_id');
    }

    public function assetHistory(): HasMany
    {
        return $this->hasMany(AssetHistory::class, 'asset_id');
    }
}
