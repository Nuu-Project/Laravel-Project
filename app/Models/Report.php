<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;

class Report extends Model
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'report_type_id',
        'user_id',
        'description',
    ];

    public function reportType()
    {
        return $this->belongsTo(ReportType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reportables()
    {
        return $this->hasMany(Reportable::class);
    }
}
