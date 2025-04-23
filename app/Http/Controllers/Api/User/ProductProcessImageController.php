<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductProcessImageController extends Controller
{
    public function processImage(Request $request): JsonResponse
    {
        $rules = [
            'image' => [
                'required',
                'image',
                'mimes:png,jpg,jpeg',
                'max:2048',
                'dimensions:max_width=3200,max_height=3200',
            ],
            'position' => ['nullable', 'integer', 'min:0', 'max:4'],
        ];

        $validatedData = $request->validate($rules);

        $position = $request->input('position', -1);

        $image = $request->file('image');

        $compressedImage = (new \App\Services\CompressedImage)->uploadCompressedImage($image);

        $compressedImagePath = 'compressed_'.uniqid().'.jpg';

        Storage::disk('temp')->put($compressedImagePath, $compressedImage->toJpeg(80));

        $encryptedImagePath = encrypt($compressedImagePath);

        return response()->json([
            'success' => true,
            'message' => '圖片上傳成功',
            'path' => $encryptedImagePath,
            'position' => $position,
        ]);
    }
}
