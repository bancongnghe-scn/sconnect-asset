<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
