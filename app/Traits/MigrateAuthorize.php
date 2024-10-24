<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;

trait MigrateAuthorize
{
    public function canPer($abilities, $throwException = true)
    {
        // Check xem co quyen chua
        if ($this->can($abilities)) {
            return true;
        }

        if (!$throwException) {
            return false;
        }

        throw $this->getMessagePermissions([$abilities]);
    }

    public function canAnyPer(array $abilities, $throwException = false): bool
    {
        foreach ($abilities as $ability) {
            if ($this->canPer($ability, false)) {
                return true;
            }
        }

        if ($throwException) {
            throw $this->getMessagePermissions($abilities);
        }

        return false;
    }

    public function getMessagePermissions(array $permissions): UnauthorizedException
    {
        $allPermissions = Cache::remember(config('cache_keys.permission_all'), now()->addDay(), function () {
            return Permission::select(['name', 'description'])->get();
        });
        $permissionsDetail = $allPermissions->whereIn('name', $permissions)->pluck('description')->toArray();

        if (!empty($permissionsDetail)) {
            $message           =  trans_choice('auth.not_have_permission', 1, [
                'permissions' => implode(', ', $permissionsDetail),
            ]);
        } else {
            $message = trans_choice('auth.not_have_permission', 0);
        }

        $exception                      = new static(403, $message, null, []);
        $exception->requiredPermissions = $permissions;

        return $exception;
    }
}
