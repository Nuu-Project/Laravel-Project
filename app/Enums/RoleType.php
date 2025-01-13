<?php

namespace App\Enums;

enum RoleType: string
{
    case Admin = 'admin';

    public function value(): string
    {
        return $this->value;
    }
}
