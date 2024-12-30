<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

class Product extends Model implements HasMedia
{
    use HasFactory, HasTags, InteractsWithMedia, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->whereNull('tags.deleted_at');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function reports()
    {
        return $this->morphToMany(Report::class, 'reportable');
    }

    public function reportables()
    {
        return $this->morphMany(Reportable::class, 'reportable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public_images');
    }

    protected $fillable = [
        'name',
        'price',
        'status',
        'description',
        'user_id',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
    ];

    public function uploadCompressedImage($image, $product)
    {
        // 保存原始圖片到臨時路徑
        $originalPath = $image->store('temp');

        // 壓縮圖片並獲取壓縮後的路徑
        $compressedPath = $this->compressImage($originalPath);

        // 確保壓縮圖片路徑有效後，將其添加到媒體庫
        if ($compressedPath) {
            $product->addMedia($compressedPath)->toMediaCollection('images');
        }

        // 清理臨時文件
        Storage::delete($originalPath);
        if (Storage::exists('temp')) {
            Storage::deleteDirectory('temp');
        }

        return $compressedPath;
    }

    protected function compressImage($imagePath)
    {
        // 使用 Storage 來處理臨時文件
        $tempFileName = 'temp/compressed_'.uniqid().'.jpg';
        $compressedPath = Storage::path($tempFileName);

        // 確保臨時目錄存在
        if (! Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        // 獲取原始文件大小
        $originalFileSize = Storage::size($imagePath);

        // 獲取原始圖片信息
        $imageInfo = getimagesize(Storage::path($imagePath));
        if (! $imageInfo) {
            throw new \Exception('無法檢測圖片類型');
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // 設置最大尺寸
        $maxWidth = 800;  // 可以根據需求調整
        $maxHeight = 500;  // 可以根據需求調整

        // 計算新的尺寸（保持比例）
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);

        // 如果圖片已經小於最大尺寸，保持原始大小
        if ($ratio >= 1) {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        } else {
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);
        }

        // 創建新的圖片
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // 載入原始圖片
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg(Storage::path($imagePath));
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng(Storage::path($imagePath));
                // 保持 PNG 透明度
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif(Storage::path($imagePath));
                break;
            default:
                throw new \Exception('不支持的圖片格式');
        }

        // 重新採樣縮放圖片（使用更好的演算法）
        imagecopyresampled(
            $newImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight
        );

        // 進行壓縮
        imagejpeg($newImage, $compressedPath, 75);  // 壓縮品質為 75

        // 釋放內存
        imagedestroy($newImage);
        imagedestroy($sourceImage);

        // 檢查壓縮效果
        $compressedSize = Storage::size($tempFileName);
        if ($compressedSize >= $originalFileSize) {
            // 刪除臨時文件
            Storage::delete($tempFileName);

            return Storage::path($imagePath);
        }

        return $compressedPath;
    }
}
