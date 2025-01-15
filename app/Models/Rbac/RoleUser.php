<?php

namespace App\Models\Rbac;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoleUser extends Model
{
    use HasFactory;
    protected $table = 'model_has_roles';

    public function menusRole(): HasMany
    {
        return $this->hasMany(MenuRole::class, 'role_id', 'role_id');
    }
}
