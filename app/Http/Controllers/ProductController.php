<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\Federal;
use App\Models\Region;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        // Only allow Super Admin, Federal Admin, and Admin roles for create, edit, store, and delete
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
    
            // Super Admin, Federal Admin, and Admin have full access
            if ($user->hasRole('Super Admin') || $user->hasRole('FederalAdmin') || $user->hasRole('Admin')) {
                return $next($request);
            }
    
            // Retrieve the product using route model binding to ensure you get the correct instance
            $product = Product::findOrFail($request->route('product'));
    
            if ($product) {
                $shop = $product->shop;
                $owner = $shop->owner;
    
                // Restrict access based on roles
                if ($user->hasRole('RegionalAdmin') && $owner->region_id != $user->region_id) {
                    return redirect()->route('products.index')->with('error', 'You do not have permission to access this product.');
                }
    
                if ($user->hasRole('ZoneAdmin') && $owner->zone_id != $user->zone_id) {
                    return redirect()->route('products.index')->with('error', 'You do not have permission to access this product.');
                }
    
                if ($user->hasRole('WoredaAdmin') && $owner->woreda_id != $user->woreda_id) {
                    return redirect()->route('products.index')->with('error', 'You do not have permission to access this product.');
                }
    
                if ($user->hasRole('KebeleAdmin') && $owner->kebele_id != $user->kebele_id) {
                    return redirect()->route('products.index')->with('error', 'You do not have permission to access this product.');
                }
    
                if ($user->hasRole('Owners') && $product->shop->owner->id != $user->id) {
                    return redirect()->route('products.index')->with('error', 'You do not have permission to access this product.');
                }
            }
    
            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy']);
    
        // Only allow users with the 'create-product' permission to access 'create' and 'store' methods
        $this->middleware('can:create-product')->only(['create', 'store']);
        
        // Only allow users with the 'edit-product' permission to access 'edit' and 'update' methods
        $this->middleware('can:edit-product')->only(['edit', 'update']);
        
        // Only allow users with the 'delete-product' permission to access 'destroy' method
        $this->middleware('can:delete-product')->only(['destroy']);
    
        // Allow access to the 'show' method for all users, but restrict based on roles
        $this->middleware(function ($request, $next) {
            if (!$this->authorizeView()) {
                return redirect()->route('products.index')->with('error', 'You do not have permission to view this product.');
            }
            return $next($request);
        })->only(['show']);
    }
    
    private function authorizeView()
    {
        $user = Auth::user();
        $product = Product::find(request()->route('product'));

        // Ensure only the owner or admins can view the product
        if ($user->hasRole('Owners') && $product->shop->owner_id !== $user->id) {
            return false; // Not authorized if the product doesn't belong to the owner
        }

        if (!$user->hasRole('Super Admin') && !$user->hasRole('Admin') &&
            !$user->hasRole('FederalAdmin') && !$user->hasRole('RegionalAdmin') &&
            !$user->hasRole('ZoneAdmin') && !$user->hasRole('WoredaAdmin') &&
            !$user->hasRole('KebeleAdmin') && !$user->hasRole('Owners') && !$user->hasRole('Customer')) {
            return false; // User does not have the necessary roles
        }

        return true;
    }
    public function index(Request $request)
    {
        $query = Product::query();
    
        // Search by name, SKU, or shop status (search across multiple attributes)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                // Search for products by name, SKU, or shop status
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhereHas('shop', function ($query) use ($request) {
                      $query->where('status', 'like', '%' . $request->search . '%');
                  });
            });
        }
    
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
    
        // Filter by federal, region, zone, woreda, and kebele
        if ($request->filled('federal')) {
            $query->whereHas('shop.federal', function ($q) use ($request) {
                $q->where('id', $request->federal);
            });
        }
        if ($request->filled('region')) {
            $query->whereHas('shop.region', function ($q) use ($request) {
                $q->where('id', $request->region);
            });
        }
        if ($request->filled('zone')) {
            $query->whereHas('shop.zone', function ($q) use ($request) {
                $q->where('id', $request->zone);
            });
        }
        if ($request->filled('woreda')) {
            $query->whereHas('shop.woreda', function ($q) use ($request) {
                $q->where('id', $request->woreda);
            });
        }
        if ($request->filled('kebele')) {
            $query->whereHas('shop.kebele', function ($q) use ($request) {
                $q->where('id', $request->kebele);
            });
        }
    
        // Filter by active shop status
        $query->whereHas('shop', function ($q) {
            $q->where('status', 'active');
        });
    
        // Sort products
        if ($request->filled('sort_by')) {
            $sortOrder = $request->input('sort_order', 'asc');
            $query->orderBy($request->sort_by, $sortOrder);
        }
    
        // Fetch products with relationships for filters
        $products = $query->with([
            'category', 'shop', 'shop.federal', 'shop.region', 'shop.zone', 'shop.woreda', 'shop.kebele'
        ])->paginate($request->get('perPage', 10));  // Pagination with dynamic per page option
    
        // Fetch related data for filters
        $categories = Category::all();
        $federals = Federal::all();
        $regions = Region::all();
        $zones = Zone::all();
        $woredas = Woreda::all();
        $kebeles = Kebele::all();
    
        return view('products.index', compact('products', 'categories', 'federals', 'regions', 'zones', 'woredas', 'kebeles'));
    }
    
    public function create()
    {
        $userId = Auth::id(); // Get authenticated user ID
        $shops = Shop::where('owner_id', $userId)->get(); // Get user's shops
       // $suppliers = Supplier::all(); // Fetch all suppliers
        $categories = $shops->pluck('category')->unique('id'); // Extract unique categories from owned shops

        return view('products.create', compact('shops', 'categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock_in' => 'required|integer|min:0',
            'stock_out' => 'required|integer|min:0',
            'purchased_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'stock_in_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $profit = $request->selling_price - $request->purchased_price;
        $profitPercent = ($profit / $request->purchased_price) * 100;

        Product::create([
            'shop_id' => $request->input('shop_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'stock_in' => $request->input('stock_in'),
            'stock_out' => $request->input('stock_out'),
            'stock_quantity' => $request->input('stock_in') + $request->input('stock_out'),
            'purchased_price' => $request->input('purchased_price'),
            'selling_price' => $request->input('selling_price'),
            'sku' => $request->input('sku'),
            'category_id' => $request->input('category_id'),
            'stock_in_date' => $request->input('stock_in_date'),
            'image' => $imagePath,
            'profit' => $profit,
            'profit_percent' => $profitPercent,
            'sales_tax' => $request->input('sales_tax', 0),
            'tax' => $request->input('tax', 0),
            'status' => $request->input('status', 'active'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $userId = Auth::id();
        $shops = Shop::where('owner_id', $userId)->get();
        $categories = Category::all();

        return view('products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock_in' => 'required|integer|min:0',
            'stock_out' => 'required|integer|min:0',
            'purchased_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'stock_in_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $profit = $request->selling_price - $request->purchased_price;
        $profitPercent = ($profit / $request->purchased_price) * 100;

        $product->update([
            'shop_id' => $request->input('shop_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'stock_in' => $request->input('stock_in'),
            'stock_out' => $request->input('stock_out'),
            'stock_quantity' => $request->input('stock_in') + $request->input('stock_out'),
            'purchased_price' => $request->input('purchased_price'),
            'selling_price' => $request->input('selling_price'),
            'sku' => $request->input('sku'),
            'category_id' => $request->input('category_id'),
            'stock_in_date' => $request->input('stock_in_date'),
            'image' => $imagePath,
            'profit' => $profit,
            'profit_percent' => $profitPercent,
            'sales_tax' => $request->input('sales_tax', 0),
            'tax' => $request->input('tax', 0),
            'status' => $request->input('status', 'active'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function show($id)
    {
        // Fetch the authenticated user
        $user = Auth::user();
    
        // Validate the user and their role
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view a product.');
        }
    
        // Check if the user has the right role to view the product
        if (!$user->hasRole('Super Admin') && !$user->hasRole('Admin')&& !$user->hasRole('Customer') && !$user->hasRole('FederalAdmin') && !$user->hasRole('RegionalAdmin') && !$user->hasRole('ZoneAdmin') && !$user->hasRole('WoredaAdmin') && !$user->hasRole('KebeleAdmin') && !$user->hasRole('Owners')) {
            return redirect()->route('products.index')->with('error', 'You do not have the required roles to view a product.');
        }
    
        // Find the product by ID
        $product = Product::findOrFail($id);
    
        // Fetch the related shop for this product
        $shop = $product->shop; // Assuming the Product model has a `shop` relationship
    
        // Optionally, you could add role-based filtering for the shop (e.g., the user should have access to the shop)
        if ($user->hasRole('Owners') && $shop->owner_id != $user->id) {
            return redirect()->route('products.index')->with('error', 'You do not have permission to view this product.');
        }
    
        // Pass both the product and shop to the view
        return view('products.show', compact('product', 'shop'));
    }
    

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
