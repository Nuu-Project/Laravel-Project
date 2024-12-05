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

    public static function tagFindOrCreate($name, $type, $slug, $orderColumn)
    {
        $tag = static::where('name->en', $name['en'])->where('type', $type)->first();

        if (! $tag) {
            $tag = new static;
            $tag->setTranslation('name', 'en', $name['en']);
            $tag->setTranslation('name', 'zh_TW', $name['zh_TW']);
            $tag->setTranslation('slug', 'en', $slug['en']);
            $tag->setTranslation('slug', 'zh_TW', $slug['zh_TW']);
            $tag->type = $type;
            $tag->order_column = $orderColumn;
            $tag->save();
        }

        return $tag;
    }
}
