<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-suppliers|edit-suppliers|delete-suppliers', ['only' => ['index','show']]);
        $this->middleware('permission:create-suppliers', ['only' => ['create','store']]);
        $this->middleware('permission:edit-suppliers', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-suppliers', ['only' => ['destroy']]);
    }
    public function index()
    {
        $suppliers = Supplier::all(); // Fetch all suppliers
        return view('suppliers.index', compact('suppliers')); // Pass suppliers to index view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create'); // Return the create view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->validated()); // Validate and create supplier
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.'); // Redirect with success message
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier')); // Pass supplier to show view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier')); // Pass supplier to edit view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated()); // Validate and update supplier
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.'); // Redirect with success message
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete(); // Delete the supplier
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.'); // Redirect with success message
    }
}
