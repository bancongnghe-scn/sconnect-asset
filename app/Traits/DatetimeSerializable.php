<?php

namespace App\Traits;

trait DatetimeSerializable
{
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
