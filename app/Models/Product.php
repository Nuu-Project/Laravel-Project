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

    public function chirps()
    {
        return $this->hasMany(Chirp::class);
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

        // 刪除臨時文件
        Storage::delete($originalPath);
        Storage::delete($compressedPath);

        // 返回壓縮後圖片的路徑，讓控制器繼續處理
        return $compressedPath;
    }

    protected function compressImage($imagePath)
    {
        // 確保壓縮圖片的目錄存在
        $compressedDirectory = storage_path('app/temp/');
        if (! is_dir($compressedDirectory)) {
            mkdir($compressedDirectory, 0777, true);  // 創建目錄
        }

        $compressedPath = storage_path('app/temp/compressed_'.uniqid().'.jpg');  // 使用唯一ID來命名壓縮圖片

        // 確保能夠檢測圖片的 MIME 類型
        $imageInfo = getimagesize(storage_path('app/'.$imagePath));
        if (! $imageInfo) {
            throw new \Exception('無法檢測圖片類型，文件可能不是有效的圖片');
        }

        $mime = $imageInfo['mime'];

        // 根據 MIME 類型創建圖片資源
        switch ($mime) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg(storage_path('app/'.$imagePath));
                break;
            case 'image/png':
                $img = imagecreatefrompng(storage_path('app/'.$imagePath));
                break;
            case 'image/gif':
                $img = imagecreatefromgif(storage_path('app/'.$imagePath));
                break;
            default:
                throw new \Exception('不支持的圖片格式：'.$mime);
        }

        // 壓縮並保存圖片
        if ($img) {
            imagejpeg($img, $compressedPath, 75); // 壓縮品質為 75
            imagedestroy($img); // 釋放內存
        }

        return $compressedPath;
    }
}
