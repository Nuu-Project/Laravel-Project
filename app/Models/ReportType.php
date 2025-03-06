<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportType extends Model
{
    use HasFactory,SoftDeletes;

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

    protected $fillable = [
        'name',
        'type',
    ];
}
