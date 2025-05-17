<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Federal;
use App\Models\Region;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
{
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
        // Define required permissions for specific actions
        $permissions = [
            'index' => 'create-user|edit-user|delete-user',
            'show' => 'create-user|edit-user|delete-user',
            'create' => 'create-user',
            'store' => 'create-user',
            'edit' => 'edit-user',
            'update' => 'edit-user',
            'destroy' => 'delete-user',
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
        // Get search input
        $search = $request->input('search');
    
        // Base query with eager loading
        $query = User::with(['region', 'zone', 'woreda', 'kebele', 'roles']);
    
        // Filter users based on roles
        if (Auth::user()->hasRole('Super Admin')) {
            // Super Admin sees all users
        } elseif (Auth::user()->hasRole('Admin')) {
            // Admin-specific logic if needed
        } elseif (Auth::user()->hasRole('FederalAdmin')) {
            // Federal Admin sees all users
        } elseif (Auth::user()->hasRole('RegionalAdmin')) {
            $query->where('region_id', Auth::user()->region_id);
        } elseif (Auth::user()->hasRole('ZoneAdmin')) {
            $query->where('zone_id', Auth::user()->zone_id);
        } elseif (Auth::user()->hasRole('WoredaAdmin')) {
            $query->where('woreda_id', Auth::user()->woreda_id);
        } elseif (Auth::user()->hasRole('KebeleAdmin')) {
            $query->where('kebele_id', Auth::user()->kebele_id);
        }
    
        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('roles', fn($q) => $q->where('name', 'LIKE', "%{$search}%")) // Search by roles
                  ->orWhereHas('region', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('zone', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('woreda', fn($q) => $q->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('kebele', fn($q) => $q->where('name', 'LIKE', "%{$search}%"));
            });
        }
    
        // Paginate results
        $users = $query->paginate(10)->appends($request->query());
    
        return view('users.index', compact('users'));
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        
        // Role hierarchy definition
        $roleHierarchy = [
            'Super Admin' => 9,
            'Admin' => 8,
            'FederalAdmin' => 7,
            'RegionalAdmin' => 6,
            'ZoneAdmin' => 5,
            'WoredaAdmin' => 4,
            'KebeleAdmin' => 3,
            'Owners' => 2,
            'Customer' => 1,
        ];

        
        // $this->authorizeUser();

         $currentUser = Auth::user();

        $federals = Federal::all();
        $regions = collect();
        $zones = collect();
        $woredas = collect();
        $kebeles = collect();

        // Fetch data based on current user's role
        if ($currentUser->hasRole('Super Admin')) {
            $regions = Region::all();
            $zones = Zone::all();
            $woredas = Woreda::all();
            $kebeles = Kebele::all();
        }
         elseif ($currentUser->hasRole('Admin') || $currentUser->hasRole('FederalAdmin')) {
            $regions = Region::all();
            $zones = Zone::all();
            $woredas = Woreda::all();
            $kebeles = Kebele::all();
        }
 elseif ($currentUser->hasRole('RegionalAdmin')) {
            $region = $currentUser->region;
            if ($region) {
                $regions = Region::where('id', $region->id)->get();
                $zones = Zone::where('region_id', $region->id)->get();
                $woredas = Woreda::whereIn('zone_id', $zones->pluck('id'))->get();
                $kebeles = Kebele::whereIn('woreda_id', $woredas->pluck('id'))->get();
            }
        } elseif ($currentUser->hasRole('ZoneAdmin')) {
            $zone = $currentUser->zone;
            if ($zone) {
                $regions = Region::where('id', $zone->region_id)->get();
                $zones = Zone::where('id', $zone->id)->get();
                $woredas = Woreda::where('zone_id', $zone->id)->get();
                $kebeles = Kebele::whereIn('woreda_id', $woredas->pluck('id'))->get();
            }
        } elseif ($currentUser->hasRole('WoredaAdmin')) {
            $woreda = $currentUser->woreda;
            if ($woreda) {
                $zones = Zone::where('id', $woreda->zone_id)->get();
                $regions = Region::where('id', $zones->first()->region_id)->get();
                $woredas = Woreda::where('id', $woreda->id)->get();
                $kebeles = Kebele::where('woreda_id', $woreda->id)->get();
            }
        } 

        elseif ($currentUser->hasRole('KebeleAdmin')) {
            $kebele = $currentUser->kebele;
            if ($kebele) {
                $woreda = Woreda::find($kebele->woreda_id);
                if ($woreda) {
                    $zones = Zone::where('id', $woreda->zone_id)->get();
                    $regions = Region::where('id', $zones->first()->region_id)->get();
                    $woredas = Woreda::where('id', $woreda->id)->get();
                    $kebeles = Kebele::where('woreda_id', $woreda->id)->get();
                }
            }
        }
        

        // Fetch allowed roles
        $roles = Role::pluck('name')->all();
        $allowedRoles = array_filter($roles, function ($role) use ($roleHierarchy, $currentUser) {
            return $currentUser->hasRole('Super Admin') || (isset($roleHierarchy[$role]) &&
                $roleHierarchy[$role] < $roleHierarchy[$currentUser->roles->first()->name]);
        });

        return view('users.create', compact('allowedRoles', 'federals', 'regions', 'zones', 'woredas', 'kebeles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $this->authorizeUser();

        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        // Same role hierarchy definition
        $roleHierarchy = [
            'Super Admin' => 9,
            'Admin' => 8,
            'FederalAdmin' => 7,
            'RegionalAdmin' => 6,
            'ZoneAdmin' => 5,
            'WoredaAdmin' => 4,
            'KebeleAdmin' => 3,
            'Owners' => 2,
            'Customer' => 1,
        ];

        $federals = Federal::all();
        $regions = collect();
        $zones = collect();
        $woredas = collect();
        $kebeles = collect();

        // Fetch data based on current user's role
        if ($currentUser->hasRole('Super Admin')) {
            $regions = Region::all();
            $zones = Zone::all();
            $woredas = Woreda::all();
            $kebeles = Kebele::all();
        } elseif ($currentUser->hasRole('Admin') || $currentUser->hasRole('FederalAdmin')) {
            $regions = Region::all();
            $zones = Zone::all();
            $woredas = Woreda::all();
            $kebeles = Kebele::all();
        } elseif ($currentUser->hasRole('RegionalAdmin')) {
            $region = $currentUser->region;
            if ($region) {
                $regions = Region::where('id', $region->id)->get();
                $zones = Zone::where('region_id', $region->id)->get();
                $woredas = Woreda::whereIn('zone_id', $zones->pluck('id'))->get();
                $kebeles = Kebele::whereIn('woreda_id', $woredas->pluck('id'))->get();
            }
        } elseif ($currentUser->hasRole('ZoneAdmin')) {
            $zone = $currentUser->zone;
            if ($zone) {
                $regions = Region::where('id', $zone->region_id)->get();
                $zones = Zone::where('id', $zone->id)->get();
                $woredas = Woreda::where('zone_id', $zone->id)->get();
                $kebeles = Kebele::whereIn('woreda_id', $woredas->pluck('id'))->get();
            }
        } elseif ($currentUser->hasRole('WoredaAdmin')) {
            $woreda = $currentUser->woreda;
            if ($woreda) {
                $zones = Zone::where('id', $woreda->zone_id)->get();
                $regions = Region::where('id', $zones->first()->region_id)->get();
                $woredas = Woreda::where('id', $woreda->id)->get();
                $kebeles = Kebele::where('woreda_id', $woreda->id)->get();
            }
        } elseif ($currentUser->hasRole('KebeleAdmin')) {
            $kebele = $currentUser->kebele;
            if ($kebele) {
                $woreda = Woreda::find($kebele->woreda_id);
                if ($woreda) {
                    $zones = Zone::where('id', $woreda->zone_id)->get();
                    $regions = Region::where('id', $zones->first()->region_id)->get();
                    $woredas = Woreda::where('id', $woreda->id)->get();
                    $kebeles = Kebele::where('woreda_id', $woreda->id)->get();
                }
            }
        }

        // Fetch allowed roles
        $roles = Role::pluck('name')->all();
        $allowedRoles = array_filter($roles, function ($role) use ($roleHierarchy, $currentUser) {
            return $currentUser->hasRole('Super Admin') || (isset($roleHierarchy[$role]) &&
                $roleHierarchy[$role] < $roleHierarchy[$currentUser->roles->first()->name]);
        });

        return view('users.edit', compact('user', 'allowedRoles', 'federals', 'regions', 'zones', 'woredas', 'kebeles'));
    }

    /**
     * Authorization method based on roles.
     */
    private function authorizeUser()
    {
        $user = Auth::user();
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'FederalAdmin', 'RegionalAdmin', 'ZoneAdmin', 'WoredaAdmin', 'KebeleAdmin'])) {
            return;
        }

        abort(403, 'Unauthorized action.');
    }


    public function store(Request $request): RedirectResponse
{
    // Authorize the user based on roles
    $this->authorizeUser();

    // Validate input
  $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
'password' => [
        'required',
        'string',
        'min:8',             // Minimum 8 characters
        'regex:/[a-z]/',      // Must contain at least one lowercase letter
        'regex:/[A-Z]/',      // Must contain at least one uppercase letter
        'regex:/[0-9]/',      // Must contain at least one digit
        'regex:/[@$!%*?&#]/', // Must contain a special character
        'confirmed',          // Must match password_confirmation field
    ],
    
    'roles' => 'required|array', // Validate roles as an array
    'roles' => 'required|string|exists:roles,name', // Validate as a single string
]);

    // Create the user with hashed password
    $input = $request->all();
    $input['password'] = Hash::make($request->password);
    $user = User::create($input);

    // Check if the authenticated user is either a guest or has no roles
    if (Auth::guest() || Auth::user()->roles->isEmpty()) {
        // Assign 'Customer' role to the new user
        $user->assignRole('Customer');
    } else {
        // Otherwise, assign roles provided in the request
        $user->assignRole($request->roles);
    }

    // Redirect with success message
    return redirect()->route('users.index')
        ->withSuccess('New user is added successfully.');
}

    

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $this->authorizeUser();

        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorizeUser();

        $user->update($request->all());
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Remove all roles and assign the new ones
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')
            ->withSuccess('$user is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeUser();

        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess('User is deleted successfully.');
    }

    /**
     * Authorize user based on roles.
     */
 
    // Import users from Excel
    public function import(Request $request)
    {
        $this->authorizeUser();
        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('users.index')->with('success', 'Users imported successfully.');
    }

    // Export users to Excel
    public function export()
    {
        $this->authorizeUser();
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
