<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'contract';
    protected $fillable = [
        'code',
        'name',
        'contract_value',
        'supplier_id',
        'signing_date',
        'from',
        'to',
        'description',
        'status',
        'type',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_CANCEL = 3;
    public const TYPE_SALE_CONTRACT = 1;
    public const TYPE_CONTRACT_PRINCIPLE = 2;
    public const TYPE_NAME = [
        self::TYPE_SALE_CONTRACT => 'Hợp đồng mua bán',
        self::TYPE_CONTRACT_PRINCIPLE => 'Hợp đồng nguyên tắc',
    ];

    public const STATUS_NAME = [
        self::STATUS_PENDING => 'Chờ duyệt',
        self::STATUS_APPROVED => 'Đã duyệt',
        self::STATUS_CANCEL => 'Hủy'
    ];
}
