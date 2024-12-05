<?php

namespace App\Enums;

enum ProductStatus: int
{
    case Active = 100;
    case Inactive = 200;

    public function label(): string
    {
        return match ($this) {
            self::Active => '上架中',
            self::Inactive => '已下架',
        };
    }
}
