<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    public const STATUS_NEW        = 1;
    public const STATUS_TRANSIT    = 2;
    public const STATUS_DELIVERED  = 3;
    public const STATUS_WAREHOUSED = 4;
    public const STATUS_CANCEL     = 5;
}
