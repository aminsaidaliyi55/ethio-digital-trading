<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
    
    $this->middleware(function ($request, $next) {
        // Define required permissions for specific actions
        $permissions = [
            'index' => 'zones-view',
            'show' => 'zones-view',
            'create' => 'zones-create',
            'store' => 'zones-create',
            'edit' => 'zones-edit',
            'update' => 'zones-edit',
            'destroy' => 'zones-delete',
        ];

        $action = $request->route()->getActionMethod();

        // Check if the user has the required permissions
        if (isset($permissions[$action]) && !auth()->user()->can($permissions[$action])) {
            return back()->with('alert', 'You do not have the right permissions to access this resource.');
        }

        return $next($request);
    });
}

    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $searchTerm = $request->get('search');
    $sortField = $request->get('sort_field', 'id');
    $sortDirection = $request->get('sort_direction', 'asc');

    $zonesQuery = Zone::query();

    // Apply filters based on the user's role
    if (Auth::user()->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
        // No filtering, show all zones
    } elseif (Auth::user()->hasRole('RegionalAdmin')) {
        $zonesQuery = $zonesQuery->where('region_id', Auth::user()->region_id);
    } elseif (Auth::user()->hasRole('ZoneAdmin')) {
        $zonesQuery = $zonesQuery->where('id', Auth::user()->zone_id);
    } elseif (Auth::user()->hasRole('WoredaAdmin')) {
        $woredaZoneId = optional(Auth::user()->woreda)->zone_id;
        $zonesQuery = $zonesQuery->where('id', $woredaZoneId);
    } elseif (Auth::user()->hasRole('KebeleAdmin')) {
        $kebeleZoneId = optional(optional(Auth::user()->kebele)->woreda)->zone_id;
        $zonesQuery = $zonesQuery->where('id', $kebeleZoneId);
    } else {
        $zonesQuery = $zonesQuery->whereRaw('1 = 0'); // Return no results for unknown roles
    }

    // Apply search and sorting
    $zones = $zonesQuery
        ->when($searchTerm, fn($query) => $query->where('name', 'like', "%{$searchTerm}%"))
        ->orderBy($sortField, $sortDirection)
        ->paginate(10);

    // Fetch admins for the current zone or region based on user role
    $adminsQuery = User::role('ZoneAdmin');

    if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('FederalAdmin')) {
        // Admins can see all ZoneAdmins
        $admins = $adminsQuery->get();
    } elseif (Auth::user()->hasRole('RegionalAdmin')) {
        // Fetch ZoneAdmins for the same region
        $admins = $adminsQuery->whereHas('zone', function($query) {
            $query->where('region_id', Auth::user()->region_id);
        })->get();
    } elseif (Auth::user()->hasRole('ZoneAdmin')) {
        // Only fetch the ZoneAdmin for the current zone
        $admins = $adminsQuery->where('zone_id', Auth::user()->zone_id)->get();
    } elseif (Auth::user()->hasRole('WoredaAdmin')) {
        // Fetch ZoneAdmins based on the zone of the user's woreda
        $woredaZoneId = optional(Auth::user()->woreda)->zone_id;
        $admins = $adminsQuery->where('zone_id', $woredaZoneId)->get();
    } elseif (Auth::user()->hasRole('KebeleAdmin')) {
        // Fetch ZoneAdmins based on the zone of the user's kebele
        $kebeleZoneId = optional(optional(Auth::user()->kebele)->woreda)->zone_id;
        $admins = $adminsQuery->where('zone_id', $kebeleZoneId)->get();
    } else {
        $admins = collect(); // Return an empty collection if no role matches
    }

    return view('zones.index', compact('zones', 'admins', 'sortField', 'sortDirection'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regions = Region::all();
        return view('zones.create', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        Zone::create($request->all());
        return redirect()->route('zones.index')->with('success', 'Zone created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        $zone->load('woredas');
        return view('zones.show', compact('zone'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
        $regions = Region::all();
        return view('zones.edit', compact('zone', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        $zone->update($request->all());
        return redirect()->route('zones.index')->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('zones.index')->with('success', 'Zone deleted successfully.');
    }

    /**
     * Show the form to assign an admin to a zone.
     */
  public function showAssignAdminForm()
{
    $user = Auth::user();
    $zonesQuery = Zone::query();
    $adminsQuery = User::role('ZoneAdmin');

    // Filter zones and admins by role
    if ($user->hasRole(['Super Admin', 'Admin', 'FederalAdmin'])) {
        // No filtering needed
    } 
    elseif ($user->hasRole('RegionalAdmin')) {
       $zonesQuery= $zonesQuery->where('region_id', $user->region_id);
        $adminsQuery=$adminsQuery->where('region_id', $user->region_id);
    }
    
    
    elseif ($user->hasRole('ZoneAdmin')) {
        $zonesQuery->where('id', $user->zone_id);
        $adminsQuery->where('zone_id', $user->zone_id);
    } 
    
    elseif ($user->hasRole('WoredaAdmin')) {
        $zoneId = optional($user->woreda)->zone_id;
        $zonesQuery->where('id', $zoneId);
        $adminsQuery->where('zone_id', $zoneId);
    } elseif ($user->hasRole('KebeleAdmin')) {
        $zoneId = optional(optional($user->kebele)->woreda)->zone_id;
        $zonesQuery->where('id', $zoneId);
        $adminsQuery->where('zone_id', $zoneId);
    } else {
        // No access
        $zonesQuery->whereRaw('1 = 0');
        $adminsQuery->whereRaw('1 = 0');
    }

    $zones = $zonesQuery->get();
    $admins = $adminsQuery->get();

    return view('zones.assignadmin', compact('zones', 'admins'));
}


    /**
     * Assign an admin to a zone.
     */
  public function storeAdmin(Request $request)
{
    $request->validate([
        'zone_id' => 'required|exists:zones,id',
        'admin_id' => 'required|exists:users,id',
    ]);

    $user = Auth::user();

    $zone = Zone::findOrFail($request->zone_id);
    $admin = User::findOrFail($request->admin_id);

    // RegionalAdmin cannot assign to zones outside their region
    if ($user->hasRole('RegionalAdmin') && $zone->region_id !== $user->region_id) {
        return redirect()->back()->withErrors(['zone_id' => 'You cannot assign admins to zones outside your region.'])->withInput();
    }

    // ZoneAdmin cannot assign to zones other than their own
    if ($user->hasRole('ZoneAdmin') && $zone->id !== $user->zone_id) {
        return redirect()->back()->withErrors(['zone_id' => 'You cannot assign admins to other zones.'])->withInput();
    }

    // Ensure selected admin is from the same region
    if ($admin->region_id !== $zone->region_id) {
        return redirect()->back()->withErrors(['admin_id' => 'The selected admin does not belong to the same region as the zone.'])->withInput();
    }

    // Optional: prevent assigning an admin already assigned to another zone
    $existingZone = Zone::where('admin_id', $admin->id)->where('id', '!=', $zone->id)->first();
    if ($existingZone) {
        return redirect()->back()->withErrors(['admin_id' => 'This admin is already assigned to another zone.'])->withInput();
    }

    // Assign admin
    $zone->admin_id = $admin->id;
    $zone->save();

    return redirect()->route('zones.index')->with('success', 'Admin assigned successfully.');
}


}
