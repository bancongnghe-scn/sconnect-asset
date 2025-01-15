<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportWarehouse extends Model
{
    use HasFactory;

    protected $table    = 'import_warehouse';
    protected $fillable = [
        'name',
        'code',
        'status',
        'description',
        'created_by',
    ];
    public const STATUS_NOT_COMPLETE = 1;
    public const STATUS_COMPLETE     = 2;
    public const STATUS_CANCEL       = 3;

    public const STATUS_NAME = [
        self::STATUS_NOT_COMPLETE => 'Chưa hoàn thành',
        self::STATUS_COMPLETE     => 'Hoàn thành',
        self::STATUS_CANCEL       => 'Hủy',
    ];

    public function importWarehouseOrders(): HasMany
    {
        return $this->hasMany(ImportWarehouseOrder::class, 'import_warehouse_id');
    }

    public function importWarehouseAssets(): HasMany
    {
        return $this->hasMany(ImportWarehouseAsset::class, 'import_warehouse_id');
    }
}
