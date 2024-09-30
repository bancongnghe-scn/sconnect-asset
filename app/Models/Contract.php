<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
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
}
