<?php

namespace App\Enums;

enum ReportType: string
{
    case Product = '商品';
    case Message = '留言';

    public function value(): string
    {
        return $this->value;
    }
}
