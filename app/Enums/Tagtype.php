<?php

namespace App\Enums;

enum Tagtype: string
{
    case Category = '課程';
    case Grade = '年級';
    case Subject = '科目';
    case Semester = '學期';

    public function getTagType(): string
    {
        return $this->getTagType;
    }
}
