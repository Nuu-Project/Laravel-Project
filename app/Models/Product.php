<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTags;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->useDisk('public_images');
    }
}
