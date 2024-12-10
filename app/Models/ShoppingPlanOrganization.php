<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingPlanOrganization extends Model
{
    use SoftDeletes;
    protected $table                                     = 'shopping_plan_organization';
    public const STATUS_OPEN_REGISTER                    = 1;
    public const STATUS_REGISTERED                       = 2;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL      = 3;
    public const STATUS_ACCOUNTANT_REVIEWING             = 4;
    public const STATUS_PENDING_MANAGER_APPROVAL         = 5;
    public const STATUS_MANAGER_APPROVAL                 = 6;
    public const STATUS_MANAGER_DISAPPROVAL              = 7;
    public const STATUS_ACCOUNT_DISAPPROVAL              = 8;
    public const STATUS_HR_HANDLE                        = 9;
    public const STATUS_HR_SYNTHETIC                     = 10;

    public const TYPE_APPROVAL    = 'approval';
    public const TYPE_DISAPPROVAL = 'disapproval';

    public function shoppingAssets(): HasMany
    {
        return $this->hasMany(ShoppingAsset::class, 'shopping_plan_organization_id');
    }

    public function shoppingPlanCompany(): BelongsTo
    {
        return $this->belongsTo(ShoppingPlanCompany::class, 'shopping_plan_company_id');
    }
}
