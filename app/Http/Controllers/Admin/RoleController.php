<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Role List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $roles = Role::where('guard_name', 'web')
            ->withCount('users')
            ->orderBy('name')
            ->paginate(15);

        return view(
            'admin.roles.index',
            compact('roles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Role
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $permissions = Permission::where(
            'guard_name',
            'web'
        )
        ->orderBy('name')
        ->get()
        ->groupBy(function ($permission) {

            return explode(
                '.',
                $permission->name
            )[0];

        });

        return view(
            'admin.roles.create',
            compact('permissions')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Role
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                'unique:roles,name',
            ],

            'display_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,name',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Role
        |--------------------------------------------------------------------------
        */

        $role = Role::create([

            'name' =>
                $validated['name'],

            'guard_name' =>
                'web',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        $permissions =
            $validated['permissions'] ?? [];


        $role->syncPermissions(
            $permissions
        );


        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Role
    |--------------------------------------------------------------------------
    */

    public function show(Role $role)
    {
        $role->load([
            'permissions',
            'users',
        ]);

        return view(
            'admin.roles.show',
            compact('role')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Role
    |--------------------------------------------------------------------------
    */

    public function edit(Role $role)
    {
        /*
        |--------------------------------------------------------------------------
        | System Administrator Protection
        |--------------------------------------------------------------------------
        */

        if (
            $role->name ===
            'system_administrator'
        ) {

            abort(
                403,
                'The System Administrator role cannot be modified.'
            );
        }


        $permissions = Permission::where(
            'guard_name',
            'web'
        )
        ->orderBy('name')
        ->get()
        ->groupBy(function ($permission) {

            return explode(
                '.',
                $permission->name
            )[0];

        });


        $role->load('permissions');


        return view(
            'admin.roles.edit',
            compact(
                'role',
                'permissions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Role
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Role $role
    ) {

        /*
        |--------------------------------------------------------------------------
        | System Administrator Protection
        |--------------------------------------------------------------------------
        */

        if (
            $role->name ===
            'system_administrator'
        ) {

            abort(
                403,
                'The System Administrator role cannot be modified.'
            );
        }


        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',

                Rule::unique(
                    'roles',
                    'name'
                )->ignore($role->id),

            ],

            'display_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,name',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Role
        |--------------------------------------------------------------------------
        */

        $role->update([

            'name' =>
                $validated['name'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Permissions
        |--------------------------------------------------------------------------
        */

        $role->syncPermissions(
            $validated['permissions'] ?? []
        );


        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Role
    |--------------------------------------------------------------------------
    */

    public function destroy(Role $role)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent System Administrator Deletion
        |--------------------------------------------------------------------------
        */

        if (
            $role->name ===
            'system_administrator'
        ) {

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    'error',
                    'System Administrator role cannot be deleted.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Deleting Roles With Users
        |--------------------------------------------------------------------------
        */

        if (
            $role->users()->exists()
        ) {

            return redirect()
                ->route('admin.roles.index')
                ->with(
                    'error',
                    'This role cannot be deleted because users are assigned to it.'
                );
        }


        $role->delete();


        return redirect()
            ->route('admin.roles.index')
            ->with(
                'success',
                'Role deleted successfully.'
            );
    }
}