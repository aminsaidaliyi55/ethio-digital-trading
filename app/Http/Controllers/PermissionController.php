<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\RedirectResponse;
use DB;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-permission|edit-permission|delete-permission', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-permission', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-permission', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-permission', ['only' => ['destroy']]);
        $this->middleware('permission:view-permission', ['only' => ['show']]);
    }

    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request)
    {
        // Get search and sort parameters from the request
        $search = $request->input('search');
        $sortField = $request->input('sort', 'name'); // Default sort by 'name'
        $sortOrder = $request->input('order', 'asc'); // Default sort order 'asc'

        // Fetch permissions based on search and sorting
        $permissions = Permission::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
        ->orderBy($sortField, $sortOrder)
        ->paginate(100); // 10 permissions per page

        // Pass permissions, search, sortField, and sortOrder to the view
        return view('permissions.index', compact('permissions', 'search', 'sortField', 'sortOrder'));
    }
  public function create()
    {
        return view('permissions.create');
    }
    /**
     * Store a newly created permission.
     */
    public function store(StorePermissionRequest $request): RedirectResponse
    {
        // Create a new permission
        $permission = Permission::create(['name' => $request->name]);

        // Check if there are any roles associated with this permission
        if ($request->has('roles')) {
            // Assign permission to roles
            $roles = Role::whereIn('id', $request->roles)->get();
            foreach ($roles as $role) {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('permissions.index')
                         ->withSuccess('New permission is added successfully.');
    }

    /**
     * Show the form for creating a new permission.
     */
  

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        // Ensure that the user cannot delete a permission if it’s assigned to themselves
        if (auth()->user()->can($permission->name)) {
            abort(403, 'CANNOT DELETE SELF-ASSIGNED PERMISSION');
        }

        // Delete the permission
        $permission->delete();

        return redirect()->route('permissions.index')
                         ->withSuccess('Permission is deleted successfully.');
    }
    
}
