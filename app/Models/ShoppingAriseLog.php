<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingAriseLog extends Model
{
    use HasFactory;
    protected $table           = 'shopping_arise_logs';
    public const ACTION_CREATE = 'create';
}
