<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'supplier';

    protected $casts = [
        'meta_data' => 'array',
    ];

    protected $fillable = [
        'name',
        'code',
        'contact',
        'address',
        'contract_user',
        'description',
        'tax_code',
        'meta_data',
        'email',
        'status',
        'created_by',
    ];

    public const STATUS_PENDING_APPROVAL = 1;
    public const STATUS_APPROVED         = 2;
    public const STATUS_CANCEL           = 3;
    public const STATUS_NAME             = [
        self::STATUS_PENDING_APPROVAL => 'Chờ duyệt',
        self::STATUS_APPROVED         => 'Đã duyệt',
        self::STATUS_CANCEL           => 'Hủy',
    ];

    public function supplierAssetIndustries(): HasMany
    {
        return $this->hasMany(SupplierAssetIndustry::class, 'supplier_id');
    }

    public function supplierAssetType(): HasMany
    {
        return $this->hasMany(SupplierAsseType::class, 'supplier_id');
    }
}
