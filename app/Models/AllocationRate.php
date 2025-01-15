<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationRate extends Model
{
    use HasFactory;

    protected $table    = 'allocation_rate';
    protected $fillable = [
        'asset_type_id',
        'level',
        'price',
        'description',
        'updated_at',
    ];

    public const TYPE_POSITION     = 1;
    public const TYPE_ORGANIZATION = 2;
}
