<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Service\Models\Org;

class Asset extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table      = 'assets';

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
        'location',
        'organization_id',
        'date_purchase',
        'seri_number',
        'import_warehouse_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $appends = [
        'location_text',
        'date_warranty',
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
    public const STATUS_MAINTAIN                = 11;

    public const STATUS_NAME = [
        self::STATUS_ACTIVE                     => 'Đang sử dụng',
        self::STATUS_PENDING                    => 'Chưa sử dụng',
        self::STATUS_NEW                        => 'Mới',
        self::STATUS_LOST                       => 'Đã mất',
        self::STATUS_CANCEL                     => 'Đã hủy',
        self::STATUS_PROPOSAL_LIQUIDATION       => 'Đề nghị thanh lý',
        self::STATUS_IN_LIQUIDATION             => 'Đang thanh lý',
        self::STATUS_LIQUIDATED                 => 'Đã thanh lý',
        self::STATUS_DAMAGED                    => 'Hỏng',
        self::STATUS_REPAIR                     => 'Đang sửa chữa',
        self::STATUS_MAINTAIN                   => 'Bảo Dưỡng',
    ];

    public const LOCATION_CS_1                  = 136;
    public const LOCATION_CS_4                  = 303;
    public const LOCATION_CS_7                  = 304;
    public const LOCATION_CS_8                  = 305;
    public const LOCATION_CS_9                  = 306;
    public const LOCATION_HCM                   = 313;
    public const LOCATION_KVC                   = 416;
    public const LOCATION_TQ_3                  = 307;
    public const LOCATION_TQ_4                  = 308;
    public const LOCATION_TQ_5                  = 309;
    public const LOCATION_TQ_6                  = 310;
    public const LOCATION_TQ_7                  = 311;
    public const LOCATION_WAREHOUSE             = 1;

    public const LOCATION_NAME = [
        self::LOCATION_CS_1                     => 'CS_Tầng 1',
        self::LOCATION_CS_4                     => 'CS_Tầng 4',
        self::LOCATION_CS_7                     => 'CS_Tầng 7',
        self::LOCATION_CS_8                     => 'CS_Tầng 8',
        self::LOCATION_CS_9                     => 'CS_Tầng 9',
        self::LOCATION_TQ_3                     => 'TQ - Tầng 3',
        self::LOCATION_TQ_4                     => 'TQ - Tầng 4',
        self::LOCATION_TQ_5                     => 'TQ - Tầng 5',
        self::LOCATION_TQ_6                     => 'TQ - Tầng 6',
        self::LOCATION_TQ_7                     => 'TQ - Tầng 7',
        self::LOCATION_HCM                      => 'HCM',
        self::LOCATION_KVC                      => 'Khu vui chơi',
        self::LOCATION_WAREHOUSE                => 'Kho công ty',
    ];

    public const LIST_MEASURE = [
        1  => 'Chiếc',
        2  => 'Cái',
        3  => 'Bộ',
        4  => 'Bình',
        5  => 'Cuộn',
        6  => 'Hộp',
        7  => 'Túi',
        8  => 'Lọ',
        9  => 'Thùng',
        10 => 'Đôi',
    ];

    public const PRICE_DEPRECIATION    = 30000000;
    public const MONTH_DEPRECIATION_36 = 36;
    public const MONTH_DEPRECIATION_12 = 12;

    protected function locationText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->location && isset(self::LOCATION_NAME[$this->location]) ? self::LOCATION_NAME[$this->location] : null,
        );
    }

    protected function dateWarranty(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->date_purchase)->addMonths($this->warranty_months)->format('Y-m-d'),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function assetRepair(): HasMany
    {
        return $this->hasMany(AssetRepair::class, 'asset_id');
    }

    public function assetHistory(): HasMany
    {
        return $this->hasMany(AssetHistory::class, 'asset_id');
    }

    public function organization()
    {
        return $this->hasOne(Org::class, 'id', 'organization_id');
    }

    public function listHistory(): HasMany
    {
        return $this->hasMany(TransferAsset::class, 'asset_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
