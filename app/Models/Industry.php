<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Industry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'industries';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
}
