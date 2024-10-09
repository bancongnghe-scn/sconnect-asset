<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    protected $table    = 'roles';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUser::class, 'role_id');
    }

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}
