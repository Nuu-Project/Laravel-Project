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

    public function chirps()
    {
        return $this->hasMany(Chirp::class);
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


    public static function getGradeTagName($grade, $semester)
    {
        $grades = [
            1 => '大一',
            2 => '大二',
            3 => '大三',
            4 => '大四',
            '其他' => '其他年級'
        ];

        $semesters = [
            1 => '上',
            2 => '下',
            '其他' => '' // 其他年級不需要學期資訊
        ];

        // 如果是其他年級，忽略學期部分
        if ($grade == '其他') {
            return $grades[$grade];
        }

        return $grades[$grade] . $semesters[$semester];
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            // 自動分離所有標籤
            $product->detachTags([]);
        });
    }
}
