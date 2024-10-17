<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->morphedByMany('App\Models\Product', 'reportable');
    }

    public function chirps()
    {
        return $this->morphedByMany('App\Models\Chirp', 'reportable');
    }

    public function users()
    {
        return $this->morphedByMany('App\Models\User', 'reportable');
    }
}
