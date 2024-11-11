<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'organization_id',
        'import_warehouse_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public const STATUS_ACTIVE           = 1;
    public const STATUS_PENDING          = 2;
    public const STATUS_REPAIR           = 3;
    public const STATUS_LOST             = 4;
    public const STATUS_CANCEL           = 5;

    public const STATUS_NAME = [
        self::STATUS_ACTIVE     => 'Hoạt động',
        self::STATUS_PENDING    => 'Tạm dừng',
        self::STATUS_REPAIR     => 'Sửa chữa',
        self::STATUS_LOST       => 'Đã mất',
        self::STATUS_CANCEL     => 'Đã hủy',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
