<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingPlanCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table    = 'shopping_plan_company';
    protected $fillable = [];
}
