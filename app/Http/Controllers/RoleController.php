<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\DB;

class RoleController extends Controller
{
    public function show()
    {
        $roles = Role::all();

        return view('admin.role', compact('roles'));
    }

    public function createRole()
    {
        $permissions = Permission::all();
        $users = User::select('name', 'id')->get();
        $roles = Role::all(); 
        return view('admin.role', compact('permissions', 'users'));
    }

    public function create(Request $request)
    {
        $role = Role::create(['name' => $request->name]);

        $permissions = $request->input('permission', []);
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        foreach ($request->users as $user) {
            $user = User::find($user);
            $user->assignRole($role->name);
        }

        return redirect()->route('admin.role')->with('roles', Role::with('permissions', 'users')->get());
    }

    public function editRole($id)
    {
        $role = Role::where('id', $id)
            ->with('permissions', 'users')
            ->first();
        $permissions = Permission::all();
        $users = User::select('name', 'id')->get();

        return view('admin.role', compact('role', 'permissions', 'users'));
    }

    public function updateRole(Request $request)
    {
        $role = Role::where('id', $request->id)->first();
        $role->name = $request->name;
        $role->update();

        $role->syncPermissions($request->permission);

        DB::table('model_has_roles')->where('role_id', $request->id)->delete();

        foreach ($request->users as $user) {
            $user = User::find($user);
            $user->assignRole($role->name);
        }

        return redirect()->route('admin.role');
    }

    public function delete($id)
    {
        Role::where('id', $id)->delete();

        return redirect()->route('admin.role');
    }
}
