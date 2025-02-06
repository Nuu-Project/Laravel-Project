<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductProcessImageController extends Controller
{
    public function processImage(Request $request)
    {
        // 只保留技術性驗證
        $request->validate([
            'image' => [
                'required',
                'image',
                'mimes:png,jpg,jpeg,gif',
                'max:2048',
                'dimensions:max_width=3200,max_height=3200',
            ]
        ]);

        $image = $request->file('image');

        $originalFilePath = Storage::disk('temp')->putFile('', $image);

        $compressedImage = (new \App\Services\CompressedImage)->uploadCompressedImage($image);

        Storage::disk('public')->put(
            $compressedImagePath = 'images/compressed_'.uniqid().'.jpg', 
            $compressedImage->toJpeg(80)
        );

        Storage::disk('temp')->delete($originalFilePath);

        encrypt($compressedImagePath);

        return response()->json([
            'success' => true,
            'message' => '圖片上傳成功',
            'path' => $compressedImagePath,
        ]);
    }
}
