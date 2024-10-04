<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractAppendix extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table                     = 'contract_appendix';
    protected $fillable                  = [
        'code',
        'name',
        'signing_date',
        'from',
        'to',
        'description',
        'contract_id',
        'status',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
    public const STATUS_PENDING          = 1;
    public const STATUS_APPROVED         = 2;
    public const STATUS_CANCEL           = 3;
    public const STATUS_NAME             = [
        self::STATUS_PENDING  => 'Chờ duyệt',
        self::STATUS_APPROVED => 'Đã duyệt',
        self::STATUS_CANCEL   => 'Hủy',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function contractFiles(): HasMany
    {
        return $this->hasMany(ContractFile::class, 'contract_id');
    }

    public function contractMonitors(): HasMany
    {
        return $this->hasMany(ContractMonitor::class, 'contract_id');
    }
}
