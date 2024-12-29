<?php

namespace App\Enums;

enum ProductStatus: int
{
    case Active = 100;
    case Inactive = 200;

    public function name(): string
    {
        return trans('product-status.'.$this->name);
    }
}
