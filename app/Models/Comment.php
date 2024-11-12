<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table    = 'comments';
    protected $fillable = [
        'target_id',
        'type',
        'message',
        'like',
        'reply',
        'created_at',
        'created_by',
    ];
    public const TYPE_SHOPPING_PLAN = 1;
}
