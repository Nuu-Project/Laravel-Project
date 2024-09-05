<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class Tag extends Model
{
    use HasFactory, HasTranslations;
    public function taggables()
    {
        return $this->morphToMany('App\Models\Product', 'taggable');
    }

    public $translatable = ['name', 'slug'];

    public static function tagFindOrCreate($name, $type, $slug, $orderColumn)
    {
        $tag = static::where('name->en', $name['en'])->where('type', $type)->first();

        if (!$tag) {
            $tag = new static;
            $tag->setTranslation('name', 'en', $name['en']);
            $tag->setTranslation('name', 'zh', $name['zh']);
            $tag->setTranslation('slug', 'en', $slug['en']);
            $tag->setTranslation('slug', 'zh', $slug['zh']);
            $tag->type = $type;
            $tag->order_column = $orderColumn;
            $tag->save();
        }

        return $tag;
    }
}
