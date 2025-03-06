<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ReportType extends Model
{
    use HasFactory,HasTranslations, SoftDeletes;

    public function products()
    {
        return $this->morphedByMany(Product::class, 'reportable');
    }

    public function messages()
    {
        return $this->morphedByMany(Message::class, 'reportable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'reportable');
    }

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'type',
    ];
}
