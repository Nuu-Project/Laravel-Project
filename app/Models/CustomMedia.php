<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class CustomMedia extends BaseMedia
{
    public function getPath(string $conversionName = ''): string
    {
        return public_path('images/' . $this->file_name);
    }

    public function getPathForConversions(string $conversionName = ''): string
    {
        return public_path('images/conversions/' . $this->file_name);
    }
}
