<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;
    protected $table                 = 'order_history';
    public const TYPE_CREATE_ORDER   = 2;
    public const TYPE_UPDATE_ORDER   = 3;
    public const TYPE_DELETE_ORDER   = 4;
    public const TYPE_COMPLETE_ORDER = 1;

}
