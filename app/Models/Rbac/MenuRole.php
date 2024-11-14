<?php

namespace App\Models\Rbac;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MenuRole extends Model
{
    protected $table = 'menu_roles';

    public function menu(): HasOne
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }
}
