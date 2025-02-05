<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reportable extends Model
{
    protected $fillable = ['report_type_id', 'reportable_id', 'reportable_type', 'user_id', 'description'];

    public function report_type()
    {
        return $this->belongsTo(ReportType::class);
    }

    public function reportable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
