<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingArise extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table                                = 'shopping_arise';
    protected $guarded                              = [];
    public const STATUS_NEW                         = 1;
    public const STATUS_PENDING_PROCESSING          = 2;
    public const STATUS_HR_PROCESSING               = 3;
    public const STATUS_HR_SYNTHETIC                = 4;
    public const STATUS_PENDING_MANAGER_HR          = 5;
    public const STATUS_PENDING_ACCOUNTANT          = 6;
    public const STATUS_PENDING_MANAGER             = 7;
    public const STATUS_COMPLETE                    = 8;
    public const STATUS_MANAGER_APPROVAL            = 9;
    public const STATUS_MANAGER_HR_DISAPPROVAL      = 10;
    public const STATUS_ACCOUNTANT_DISAPPROVAL      = 11;
    public const STATUS_MANAGER_DISAPPROVAL         = 12;

    public function assets(): HasMany
    {
        return $this->hasMany(ShoppingAsset::class, 'shopping_arise_id');
    }
}
