<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingArise extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table                                = 'shopping_arise';
    public const STATUS_NEW                         = 1;
    public const STATUS_HR_HANDLE                   = 2;
    public const STATUS_HR_SYNTHETIC                = 3;
    public const STATUS_PENDING_MANAGER_HR          = 4;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL = 5;
    public const STATUS_PENDING_MANAGER_APPROVAL    = 6;
    public const STATUS_COMPLETE                    = 7;
    public const STATUS_MANAGER_APPROVAL            = 8;
    public const STATUS_MANAGER_HR_DISAPPROVAL      = 9;
    public const STATUS_ACCOUNTANT_DISAPPROVAL      = 10;
    public const STATUS_MANAGER_DISAPPROVAL         = 11;
}
