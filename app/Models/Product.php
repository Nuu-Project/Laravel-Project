<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;
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

    public function uploadCompressedImage($image)
    {
        $tempFileName = 'temp/compressed_'.uniqid().'.jpg';

        $manager = new ImageManager(Driver::class);
        $image = $manager->read($image->getRealPath());

        $image->scale(height: 600);

        $encoded = $image->encode(new JpegEncoder(quality: 50));

        Storage::put($tempFileName, $encoded);

        return Storage::path($tempFileName);
    }
}
