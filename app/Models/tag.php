<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag extends Model
{
    use HasFactory;
    public function taggables()
    {
        return $this->morphToMany('App\Models\Product', 'taggable');
    }
}
