<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public const TYPE_SHOPPING_PLAN_COMPANY      = 1;
    public const TYPE_SHOPPING_PLAN_ORGANIZATION = 2;
    public const TYPE_PLAN_MAINTAIN              = 3;
    public const TYPE_PLAN_LIQUIDATION           = 4;

    public function commentFiles(): HasMany
    {
        return $this->hasMany(CommentFile::class, 'comment_id');
    }
}
