<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\Tag;

class Product extends Model
{
    use HasFactory;
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }
}
