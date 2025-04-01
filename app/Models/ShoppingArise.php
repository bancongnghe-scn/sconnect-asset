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

    protected $table                                = 'shopping_plan_company';
    protected $guarded                              = [];

    public const STATUS_NEW                         = 1;
    public const STATUS_PENDING_PROCESSING          = 2;
    public const STATUS_HR_PROCESSING               = 3;
    public const STATUS_HR_SYNTHETIC                = 4;
    public const STATUS_PENDING_MANAGER_HR          = 5;
    public const STATUS_PENDING_ACCOUNTANT          = 6;
    public const STATUS_PENDING_MANAGER             = 7;
    public const STATUS_COMPLETE                    = 8;
    public const GET_OF_ORGANIZATION                = 1;

    public function assets(): HasMany
    {
        return $this->hasMany(ShoppingAsset::class, 'shopping_plan_company_id');
    }
}
