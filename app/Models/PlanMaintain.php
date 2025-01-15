<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanMaintain extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table    = 'plan_maintain';
    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'note',
        'start_time',
        'end_time',
        'maintain_costs',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];


    public const TYPE_INVENTORY   = 1;
    public const TYPE_MAINTAIN    = 2;
    public const TYPE_LIQUIDATION = 3;

    public const STATUS_NEW                        = 0;
    public const STATUS_PENDING                    = 1;
    public const STATUS_APPROVAL                   = 2;
    public const STATUS_REJECT                     = 3;
    public const STATUS_MAINTAINING                = 4;
    public const STATUS_COMPLETE_MAINTAIN          = 5;

    public const STATUS_NAME = [
        self::STATUS_NEW            => 'Mới tạo',
        self::STATUS_PENDING        => 'Chờ duyệt',
        self::STATUS_APPROVAL       => 'Đã duyệt',
        self::STATUS_REJECT         => 'Từ chối',
    ];

    public function planMaintainAsset(): HasMany
    {
        return $this->hasMany(PlanMaintainAsset::class, 'plan_maintain_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function planMaintainOrganizations(): HasMany
    {
        return $this->hasMany(PlanMaintainOrganization::class, 'plan_maintain_id');
    }

    public function planMaintainSuppliers(): HasMany
    {
        return $this->hasMany(PlanMaintainSupplier::class, 'plan_maintain_id');
    }
}
