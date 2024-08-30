<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Spatie\Permission\Models\Permission;

class Role extends Model
{
    use HasFactory;

    public function permissions(){
        return  $this->belongsToMany(Permission::class);
    }
    public function model(){
        return  $this->belongsToMany(Model::class);
    }

}
