<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'order_column'];

    use HasFactory, HasTranslations;
    use SoftDeletes;

    public function taggables()
    {
        return $this->morphToMany('App\Models\Product', 'taggable');
    }

    public $translatable = ['name', 'slug'];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];
}
