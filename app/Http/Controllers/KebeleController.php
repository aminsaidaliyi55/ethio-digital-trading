<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebeleController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
        // Define required permissions for specific actions
        $permissions = [
            'index' => 'kebeles-view',
            'show' => 'kebeles-view',
            'create' => 'kebeles-create',
            'store' => 'kebeles-create',
            'edit' => 'kebeles-edit',
            'update' => 'kebeles-edit',
            'destroy' => 'kebeles-delete',
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
    
    // Initialize the query for kebeles
    $kebelesQuery = Kebele::query();
    
    // Apply filters based on the user's role
    if (Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
        // Fetch all kebeles
        $kebelesQuery = $kebelesQuery;
    } elseif (Auth::user()->hasRole('RegionalAdmin')) {
        // Filter kebeles by region through woreda -> zone -> region relationship
        $kebelesQuery = $kebelesQuery->whereHas('woreda.zone.region', function ($query) {
            $query->where('id', Auth::user()->region_id);
        });
    } elseif (Auth::user()->hasRole('ZoneAdmin')) {
        // Filter kebeles by zone through woreda -> zone relationship
        $kebelesQuery = $kebelesQuery->whereHas('woreda.zone', function ($query) {
            $query->where('id', Auth::user()->zone_id);
        });
    } elseif (Auth::user()->hasRole('WoredaAdmin')) {
        // Filter kebeles by woreda
        $kebelesQuery = $kebelesQuery->where('woreda_id', Auth::user()->woreda_id);
    } else {
        // Return an empty query if no roles match
        $kebelesQuery = $kebelesQuery->whereRaw('1 = 0');
    }
    
    // Apply search and sorting for kebeles
    $kebeles = $kebelesQuery
        ->when($searchTerm, fn($query) => $query->where('name', 'like', "%{$searchTerm}%"))
        ->orderBy($sortField, $sortDirection)
        ->paginate(10);

    // Initialize the query for admins
    $adminsQuery = User::role('KebeleAdmin');
    
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
    } elseif (Auth::user()->hasRole('WoredaAdmin')) {
        // Filter admins by woreda
        $adminsQuery = $adminsQuery->where('woreda_id', Auth::user()->woreda_id);
    } else {
        // Return an empty query if no roles match
        $adminsQuery = $adminsQuery->whereRaw('1 = 0');
    }

    // Fetch the admins based on the query
    $admins = $adminsQuery->get();

    // Return the view with kebeles and admins
    return view('kebeles.index', compact('kebeles', 'admins', 'sortField', 'sortDirection'));
}

    
    public function create()
    {
        $woredas = match (true) {
            Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin']) => Zone::all(),
            Auth::user()->hasRole('RegionalAdmin') => Zone::where('region_id', Auth::user()->region_id)->get(),
            Auth::user()->hasRole('ZoneAdmin') => Zone::where('id', Auth::user()->zone_id)->get(),
            Auth::user()->hasRole('WoredaAdmin') => Woreda::where('id', Auth::user()->woreda_id)->get(),
            Auth::user()->hasRole('KebeleAdmin') => Kebele::where('id', Auth::user()->kebele_id)->get(),
            default => collect()
        };

        return view('kebeles.create', compact('woredas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'woreda_id' => 'required|exists:woredas,id',
            'name' => 'required|string|max:255',
        ]);

        Kebele::create($request->all());

        return redirect()->route('kebeles.index')->with('success', 'Kebele created successfully.');
    }

    public function show(Kebele $Kebele, Request $request)
    {
        $kebeles = $Kebele->kebeles()
            ->when($request->get('search'), fn($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy($request->get('sort', 'name'), $request->get('direction', 'asc'))
            ->paginate(10);

        return view('kebeles.show', compact('Kebele', 'kebeles'));
    }

    public function edit(Kebele $kebele)
    {
        $woredas = match (true) {
            Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin']) => Zone::all(),
            Auth::user()->hasRole('RegionalAdmin') => Zone::where('region_id', Auth::user()->region_id)->get(),
            Auth::user()->hasRole('ZoneAdmin') => Zone::where('id', Auth::user()->zone_id)->get(),
            Auth::user()->hasRole('WoredaAdmin') => Woreda::where('id', Auth::user()->woreda_id)->get(),
            Auth::user()->hasRole('WoredaAdmin') => Woreda::where('id', Auth::user()->woreda_id)->get(),
            default => collect()
        }; 

        return view('kebeles.edit', compact('kebele', 'woredas'));
    }

    public function update(Request $request, Kebele $woreda)
    {
        $request->validate([
            'woreda_id' => 'required|exists:woredas,id',
            'name' => 'required|string|max:255',
        ]);

        $woreda->update($request->all());

        return redirect()->route('kebeles.index')->with('success', 'Kebele updated successfully.');
    }

    public function destroy(Kebele $Kebele)
    {
        $Kebele->delete();

        return redirect()->route('kebeles.index')->with('success', 'Kebele deleted successfully.');
    }

 public function showAssignAdminForm()
{
    $user = Auth::user();

    $kebelesQuery = Kebele::query();
    $adminsQuery = User::role('KebeleAdmin');

    if ($user->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
        // no restriction
    } elseif ($user->hasRole('RegionalAdmin')) {
        $kebelesQuery->whereHas('woreda.zone', fn($q) => $q->where('region_id', $user->region_id));
        $adminsQuery->where('region_id', $user->region_id);
    } elseif ($user->hasRole('ZoneAdmin')) {
        $kebelesQuery->whereHas('woreda', fn($q) => $q->where('zone_id', $user->zone_id));
        $adminsQuery->where('zone_id', $user->zone_id);
    } elseif ($user->hasRole('WoredaAdmin')) {
        $kebelesQuery->where('woreda_id', $user->woreda_id);
        $adminsQuery->where('woreda_id', $user->woreda_id);
    } elseif ($user->hasRole('KebeleAdmin')) {
        $kebelesQuery->where('id', $user->kebele_id);
        $adminsQuery->where('kebele_id', $user->kebele_id);
    } else {
        $kebelesQuery->whereRaw('1 = 0');
        $adminsQuery->whereRaw('1 = 0');
    }

    $kebeles = $kebelesQuery->get();
    $admins = $adminsQuery->get();

    return view('kebeles.assignadmin', compact('kebeles', 'admins'));
}



    public function storeAdmin(Request $request)
    {
        $request->validate([
            'kebele_id' => 'required|exists:kebeles,id',
            'admin_id' => 'required|exists:users,id',
        ]);

        $kebele = Kebele::findOrFail($request->kebele_id);
        $kebele->admin_id = $request->admin_id;
        $kebele->save();

        return redirect()->route('kebeles.index')->with('success', 'Admin assigned successfully.');
    }

 
}
