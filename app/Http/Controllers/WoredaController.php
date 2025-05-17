<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WoredaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            // Define required permissions for specific actions
            $permissions = [
                'index' => 'woredas-view',
                'show' => 'woredas-view',
                'create' => 'woredas-create',
                'store' => 'woredas-create',
                'edit' => 'woredas-edit',
                'update' => 'woredas-edit',
                'destroy' => 'woredas-delete',
            ];

            $action = $request->route()->getActionMethod();

            // Check if the user has the required permissions
            if (isset($permissions[$action]) && !auth()->user()->can($permissions[$action])) {
                return back()->with('alert', 'You do not have the right permissions to access this resource.');
            }

            return $next($request);
        });
    }

   public function index(Request $request)
{
    $searchTerm = $request->get('search');
    $sortField = $request->get('sort_field', 'id');
    $sortDirection = $request->get('sort_direction', 'asc');
    
    // Initialize the query for woredas
    $woredasQuery = Woreda::query();
    
    // Apply filters based on the user's role
    if (Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
        // Fetch all woredas
        $woredasQuery = $woredasQuery;
    } elseif (Auth::user()->hasRole('RegionalAdmin')) {
        // Filter woredas by region through zone -> region relationship
        $woredasQuery = $woredasQuery->whereHas('zone.region', function ($query) {
            $query->where('id', Auth::user()->region_id);
        });
    } elseif (Auth::user()->hasRole('ZoneAdmin')) {
        // Filter woredas by zone
        $woredasQuery = $woredasQuery->where('zone_id', Auth::user()->zone_id);
    } else {
        // Return an empty query if no roles match
        $woredasQuery = $woredasQuery->whereRaw('1 = 0');
    }
    
    // Apply search and sorting for woredas
    $woredas = $woredasQuery
        ->when($searchTerm, fn($query) => $query->where('name', 'like', "%{$searchTerm}%"))
        ->orderBy($sortField, $sortDirection)
        ->paginate(10);

    // Initialize the query for admins
    $adminsQuery = User::role('WoredaAdmin');
    
    // Apply filters for admins based on the authenticated user's role
    if (Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
        // All admins are visible
        $adminsQuery = $adminsQuery;
    } elseif (Auth::user()->hasRole('RegionalAdmin')) {
        // Filter admins by region
        $adminsQuery = $adminsQuery->where('region_id', Auth::user()->region_id);
    } elseif (Auth::user()->hasRole('ZoneAdmin')) {
        // Filter admins by zone
        $adminsQuery = $adminsQuery->where('zone_id', Auth::user()->zone_id);
    } else {
        // Return an empty query if no roles match
        $adminsQuery = $adminsQuery->whereRaw('1 = 0');
    }

    // Fetch the admins based on the query
    $admins = $adminsQuery->get();

    // Return the view with woredas and admins
    return view('woredas.index', compact('woredas', 'admins', 'sortField', 'sortDirection'));
}


    public function create()
    {
        $zones = match (true) {
            Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin']) => Zone::all(),
            Auth::user()->hasRole('RegionalAdmin') => Zone::where('region_id', Auth::user()->region_id)->get(),
            Auth::user()->hasRole('ZoneAdmin') => Zone::where('id', Auth::user()->zone_id)->get(),
            default => collect()
        };

        return view('woredas.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
        ]);

        Woreda::create($request->all());

        return redirect()->route('woredas.index')->with('success', 'Woreda created successfully.');
    }

    public function show(Woreda $woreda, Request $request)
    {
        $kebeles = $woreda->kebeles()
            ->when($request->get('search'), fn($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy($request->get('sort', 'name'), $request->get('direction', 'asc'))
            ->paginate(10);

        return view('woredas.show', compact('woreda', 'kebeles'));
    }

    public function edit(Woreda $woreda)
    {
        $zones = Zone::all();

        return view('woredas.edit', compact('woreda', 'zones'));
    }

    public function update(Request $request, Woreda $woreda)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
        ]);

        $woreda->update($request->all());

        return redirect()->route('woredas.index')->with('success', 'Woreda updated successfully.');
    }

    public function destroy(Woreda $woreda)
    {
        $woreda->delete();

        return redirect()->route('woredas.index')->with('success', 'Woreda deleted successfully.');
    }

    public function showAssignAdminForm()
    {
        $user = Auth::user();

        $woredasQuery = Woreda::query();
        $adminsQuery = User::role('WoredaAdmin');

        if ($user->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
            // All woredas and all WoredaAdmins
        } 
        elseif ($user->hasRole('RegionalAdmin')) {
            // Woredas and admins under the same region
            $woredasQuery->whereHas('zone.region', fn($query) =>
                $query->where('id', $user->region_id)
            );
            $adminsQuery->where('region_id', $user->region_id);

        } 
        elseif ($user->hasRole('ZoneAdmin')) {
            // Woredas and admins under the same zone
            $woredasQuery->where('zone_id', $user->zone_id);
            $adminsQuery->where('zone_id', $user->zone_id);
        } else {
            // No access
            $woredasQuery->whereRaw('1 = 0');
            $adminsQuery->whereRaw('1 = 0');
        }

        $woredas = $woredasQuery->get();
        $admins = $adminsQuery->get();

        return view('woredas.assignadmin', compact('woredas', 'admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'woreda_id' => 'required|exists:woredas,id',
            'admin_id' => 'required|exists:users,id',
        ]);

        $woreda = Woreda::findOrFail($request->woreda_id);
        $woreda->admin_id = $request->admin_id;
        $woreda->save();

        return redirect()->route('woredas.index')->with('success', 'Admin assigned successfully.');
    }
}
