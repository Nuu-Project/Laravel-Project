<?php

namespace App\Services;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;

class CompressedImage
{
    public function uploadCompressedImage(string $image): ImageInterface
    {
        return Image::read($image)
            ->scale(800);
    }
}
