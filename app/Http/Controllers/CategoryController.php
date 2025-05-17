<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-category|edit-category|delete-category|view-product', ['only' => ['index','show']]);
       $this->middleware('permission:create-category', ['only' => ['create','store']]);
       $this->middleware('permission:edit-category', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-category', ['only' => ['destroy']]);
       $this->middleware('permission:view-category', ['only' => ['show']]);
    }
    /**
     * Display a listing of the resource.
     */
 public function index()
    {
        // Paginate the categories
        $categories = Category::paginate(10); // Change 10 to whatever number you want per page
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());
        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    // Other methods (edit, update, destroy)...


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
          return view('category.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        return view('category.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
        
         $category->update($request->all());
        return redirect()->route('category.index')
                ->withSuccess('category is updated successfully.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        category->delete();
        return redirect()->route('category.index')
                ->withSuccess('Category is deleted successfully.');
    
    }
}
