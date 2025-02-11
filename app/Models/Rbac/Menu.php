<?php

namespace App\Models\Rbac;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'icon',
        'url',
        'order',
        'parent_id',
    ];

    public function menuRoles(): HasMany
    {
        return $this->hasMany(MenuRole::class, 'menu_id');
    }

    public function menuUsers(): HasMany
    {
        return $this->hasMany(MenuUser::class, 'menu_id');
    }
}
