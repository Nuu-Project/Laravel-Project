<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
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
