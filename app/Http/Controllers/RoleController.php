<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    
        $this->middleware(function ($request, $next) {
            // Define required permissions for specific actions
            $permissions = [
                'index' => 'create-role|edit-role|delete-role',
                'show' => 'create-role|edit-role|delete-role',
                'create' => 'create-role',
                'store' => 'create-role',
                'edit' => 'edit-role',
                'update' => 'edit-role',
                'destroy' => 'delete-role',
            ];
    
            $action = $request->route()->getActionMethod();
    
            // Check if the user has the required permission for the action
            if (isset($permissions[$action])) {
                $requiredPermissions = explode('|', $permissions[$action]);
                $hasPermission = false;
    
                foreach ($requiredPermissions as $permission) {
                    if (auth()->user()->can($permission)) {
                        $hasPermission = true;
                        break;
                    }
                }
    
                if (!$hasPermission) {
                    return back()->with('alert', 'You do not have the right permissions to access this resource.');
                }
            }
    
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query();
    
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
    
        $roles = $query->paginate(10);
    
        return view('roles.index', compact('roles'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('roles.create', [
            'permissions' => Permission::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
        
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
                ->withSuccess('New role is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        $rolePermissions = Permission::join("role_has_permissions","permission_id","=","id")
            ->where("role_id",$role->id)
            ->select('name')
            ->get();
        return view('roles.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        if($role->name=='Super Admin')
        {

            abort(403, 'SUPER ADMIN ROLE CAN NOT BE EDITED');


        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$role->id)
            ->pluck('permission_id')
            ->all();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $input = $request->only('name');

        $role->update($input);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();

        $role->syncPermissions($permissions);    
        
              return redirect()->route('roles.index')
                ->withSuccess('Role is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if($role->name=='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }
        if(auth()->user()->hasRole($role->name)){
            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        }
        $role->delete();
        return redirect()->route('roles.index')
                ->withSuccess('Role is deleted successfully.');
    }
}