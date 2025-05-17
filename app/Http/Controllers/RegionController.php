<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Federal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware(function ($request, $next) {
            // Define required permissions for specific actions
            $permissions = [
                'index' => 'regions-view',
                'show' => 'regions-view',
                'create' => 'regions-create',
                'store' => 'regions-create',
                'storeAdmin' => 'regions-create',
                'edit' => 'regions-edit',
                'update' => 'regions-edit',
                'destroy' => 'regions-delete',
            ];
    
            $action = $request->route()->getActionMethod();
    
            // Check if the user has the required permissions
            if (isset($permissions[$action]) && !auth()->user()->can($permissions[$action])) {
                // Redirect to homepage with an error message
                return redirect('/')->with('alert', 'You do not have the right permissions to access this resource.');
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

        $regions = Region::query()
            ->when($searchTerm, fn ($query, $searchTerm) => $query->where('name', 'like', '%' . $searchTerm . '%'))
            ->orderBy($sortField, $sortDirection)
            ->paginate(10);

        $admins = User::role('RegionalAdmin')->get();

        return view('regions.index', compact('regions', 'admins', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $federals = Federal::all();
        return view('regions.create', compact('federals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'federal_id' => 'required|exists:federals,id',
        ]);

        Region::create($request->all());
        return redirect()->route('regions.index')->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        $region->load('zones');
        return view('regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        $federals = Federal::all();
        return view('regions.edit', compact('region', 'federals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'federal_id' => 'required|exists:federals,id',
        ]);

        $region->update($request->all());
        return redirect()->route('regions.index')->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('regions.index')->with('success', 'Region deleted successfully.');
    }

    /**
     * Show the form to assign an admin to a region.
     */
    public function showAssignAdminForm()
    {
        $regions = Region::all();
        $admins = User::whereHas('roles', fn ($query) => $query->where('name', 'RegionalAdmin'))->get();

        return view('regions.assignadmin', compact('regions', 'admins'));
    }

    /**
     * Assign an admin to a region.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'admin_id' => 'required|exists:users,id',
        ]);

        $region = Region::findOrFail($request->region_id);
        $region->admin_id = $request->admin_id;
        $region->save();

        return redirect()->route('regions.index')->with('success', 'Admin assigned successfully.');
    }
}
