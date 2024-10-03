<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractAppendix extends Model
{
    use HasFactory;
    protected $table                     = 'contract_appendix';
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
}
