<?php

namespace App\Traits;

use Spatie\Permission\Exceptions\UnauthorizedException;

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

        throw new UnauthorizedException(403, 'Bạn không có quyền thực hiện thao tác này');
    }

    public function canAnyPer(array $abilities, $throwException = true): bool
    {
        foreach ($abilities as $ability) {
            if ($this->canPer($ability, false)) {
                return true;
            }
        }

        if ($throwException) {
            throw new UnauthorizedException(403, 'Bạn không có quyền thực hiện thao tác này');
        }

        return false;
    }
}
