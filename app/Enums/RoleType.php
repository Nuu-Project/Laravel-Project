<?php

namespace App\Enums;

enum RoleType: string
{
    case Guest = 'guest';
    case User = 'user';
    case Admin = 'admin';

    public function value(): string
    {
        return $this->value;
    }
}
