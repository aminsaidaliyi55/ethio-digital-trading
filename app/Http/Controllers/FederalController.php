<?php

namespace App\Http\Controllers;

use App\Models\Federal; // Ensure you import the Federal model
use App\Http\Requests\UpdateFederalRequest; // Ensure you import the UpdateFederalRequest
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use Illuminate\Validation\Rule;

class FederalController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');

    $this->middleware(function ($request, $next) {
        if (!auth()->user()->can('federals-create|federals-edit|federals-view|federals-delete')) {
            return back()->with('alert', 'You do not have the right permissions to access this resource.');
        }
        return $next($request);
    });
    
    $this->middleware('permission:federals-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:federals-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:federals-delete', ['only' => ['destroy']]);
    $this->middleware('permission:federals-view', ['only' => ['show']]);
}


    /**
     * Display a listing of the resource.
     */

public function index(Request $request)
{
    $admins = User::whereHas('roles', function ($query) {
        $query->where('name', 'Federaladmin');
    })->get();

    $searchTerm = $request->get('search');
    $sortField = $request->get('sort_field', 'id');
    $sortDirection = $request->get('sort_direction', 'asc');

    $federals = Federal::query()
        ->when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name', 'like', '%' . $searchTerm . '%');
        })
        ->orderBy($sortField, $sortDirection)
        ->paginate(10);

    $federalAdmins = User::role('Federal Admin')->get(); // Adjust role as per your setup

    return view('federals.index', compact('admins','federals', 'federalAdmins', 'sortField', 'sortDirection'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('federals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Federal::create($request->all());
        
        return redirect()->route('federals.index')->with('success', 'Federals created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Federal $federal)
    {
            $federal->load('regions'); // Eager load regions

        return view('federals.show', [
            'federal' => $federal
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Federal $federal)
    {
        return view('federals.edit', [
            'federal' => $federal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $federal = Federal::findOrFail($id);
    
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Update federal
        $federal->name = $request->input('name');
        $federal->save();
    
        return redirect()->route('federals.index')->with('success', 'Federal updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Federal $federal)
    {
        $federal->delete();
        return redirect()->route('federals.index')
                ->with('success', 'Federal deleted successfully.');
    }
    public function showAssignAdminForm()
    {
        $federals = Federal::all(); // Get all Federal entities
    
        // Filter users manually who have the role 'Federaladmin'
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Federaladmin');
        })->get();
    
        return view('federals.assignadmin', compact('federals', 'admins'));
    }
    
    

    public function assignadmin(Request $request)
    {
        $request->validate([
            'federal_id' => 'required|exists:federals,id',
            'admin_id' => 'required|exists:users,id',
        ]);
    
        $federal = Federal::findOrFail($request->federal_id);
        $federal->admin_id = $request->admin_id;
        $federal->save();
    
        return redirect()->route('federals.index')->with('success', 'Admin assigned successfully.');
    }
    


}
