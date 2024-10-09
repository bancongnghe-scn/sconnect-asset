<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    use HasFactory;
    protected $table    = 'permissions';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function usersPermission(): HasMany
    {
        return $this->hasMany(UserPermission::class, 'permission_id');
    }

    public function rolesPermission(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'permission_id');
    }
}
