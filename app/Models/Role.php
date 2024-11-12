<?php

namespace App\Models;

use DragonCode\Contracts\Cashier\Resources\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;

class Role extends SpatieRole implements RoleContract
{
    use HasFactory,HasRoles;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }

    public function model(): BelongsToMany
    {
        return $this->belongsToMany(Model::class);
    }
}
