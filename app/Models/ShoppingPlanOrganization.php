<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingPlanOrganization extends Model
{
    use SoftDeletes;
    protected $table                                = 'shopping_plan_organization';
    public const STATUS_REGISTER                    = 1;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL = 2;
    public const STATUS_ACCOUNTANT_REVIEW           = 3;
    public const STATUS_PENDING_MANAGER_APPROVAL    = 4;
    public const STATUS_APPROVAL                    = 5;
    public const STATUS_DISAPPROVAL                 = 6;

    public function shoppingAssetsYear(): HasMany
    {
        return $this->hasMany(ShoppingAsset::class, 'shopping_plan_organization_id')->orderBy('month');
    }
}
