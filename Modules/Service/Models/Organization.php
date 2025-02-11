<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends BaseModel
{
    protected $table = 'organizations';

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_id', 'id');
    }
}
