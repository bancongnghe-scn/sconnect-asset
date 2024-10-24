<?php

namespace App\Traits;

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

        throw new static(403, 'Bạn không có quyền thực hiện thao tác này', null, []);
    }

    public function canAnyPer(array $abilities, $throwException = false): bool
    {
        foreach ($abilities as $ability) {
            if ($this->canPer($ability, false)) {
                return true;
            }
        }

        if ($throwException) {
            throw new static(403, 'Bạn không có quyền thực hiện thao tác này', null, []);
        }

        return false;
    }
}
