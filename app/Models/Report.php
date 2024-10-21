<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->morphedByMany(Product::class, 'reportable');
    }

    public function chirps()
    {
        return $this->morphedByMany(Chirp::class, 'reportable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'reportable');
    }
}
