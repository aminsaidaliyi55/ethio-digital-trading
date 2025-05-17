<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Federal;
use App\Models\Region;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Validation\Rule;
use App\Models\User;

class ShopController extends Controller
{
    public function __construct()
    {
        // Middleware for role-based access control
        $this->middleware('role:Super Admin|Admin|FederalAdmin|RegionalAdmin|ZoneAdmin|WoredaAdmin|KebeleAdmin|Owners|Customer');
    }
    
    public function edit($id)
    {
        // Find the shop by ID
        $shop = Shop::findOrFail($id);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Super Admin, Admin, and FederalAdmin have full access to edit any shop
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('FederalAdmin')) {
            return $this->prepareShopData($shop);
        }
    
        // Check for specific roles and regional relationships
        if ($user->hasRole('RegionalAdmin') && $shop->region_id == $user->region_id) {
            return $this->prepareShopData($shop);
        }
    
        if ($user->hasRole('ZoneAdmin') && $shop->zone_id == $user->zone_id) {
            return $this->prepareShopData($shop);
        }
    
        if ($user->hasRole('WoredaAdmin') && $shop->woreda_id == $user->woreda_id) {
            return $this->prepareShopData($shop);
        }
    
        if ($user->hasRole('KebeleAdmin') && $shop->kebele_id == $user->kebele_id) {
            return $this->prepareShopData($shop);
        }
    
        // Redirect with an error message if the user lacks permission
        return redirect()->route('shops.index')->with('error', 'You do not have permission to edit this shop.');
    }
     
    public function update(Request $request, $id)
    {
        // Validation for updating shop
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
            'phone' => 'required|string|max:15',
            'total_capital' => 'required|numeric',
            'opening_hours' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            // Add more validation as needed
        ]);
    
        // Find the shop by ID
        $shop = Shop::findOrFail($id);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Check permissions for Super Admin, Admin, and FederalAdmin
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin') || $user->hasRole('FederalAdmin')) {
            return $this->updateShopData($shop, $validated);
        }
    
        // Check if the user is authorized to update based on their role
        if ($user->hasRole('RegionalAdmin') && $shop->region_id == $user->region_id) {
            return $this->updateShopData($shop, $validated);
        }
    
        if ($user->hasRole('ZoneAdmin') && $shop->zone_id == $user->zone_id) {
            return $this->updateShopData($shop, $validated);
        }
    
        if ($user->hasRole('WoredaAdmin') && $shop->woreda_id == $user->woreda_id) {
            return $this->updateShopData($shop, $validated);
        }
    
        if ($user->hasRole('KebeleAdmin') && $shop->kebele_id == $user->kebele_id) {
            return $this->updateShopData($shop, $validated);
        }
    
        // If the user doesn't have the right permissions, redirect with an error message
        return redirect()->route('shops.index')->with('error', 'You do not have permission to update this shop.');
    }
    
    // Helper function to prepare shop data for rendering the view
    private function prepareShopData($shop)
    {
        $owners = User::whereHas('roles', function($query) {
            $query->where('name', 'Owners');
        })->get();
    
        $categories = Category::all();
        $federals = Federal::all();
        $regions = Region::all();
        $zones = Zone::all();
        $woredas = Woreda::all();
        $kebeles = Kebele::all();
    
        // Return the view with the data
        return view('shops.edit', compact('shop', 'owners', 'categories', 'federals', 'regions', 'zones', 'woredas', 'kebeles'));
    }
    
    // Helper function to update shop data
    private function updateShopData($shop, $validatedData)
    {
        // Update the shop data with validated input
        $shop->update($validatedData);
    
        // Return to the shop index page with a success message
        return redirect()->route('shops.index')->with('success', 'Shop updated successfully.');
    }
    
    

    public function index(Request $request)
{
    $user = Auth::user(); // Get the currently authenticated user

    // If the user does not have the right role, redirect with an error message
   

    $query = Shop::query();
    $searchTerm = $request->input('search', ''); // Default to an empty string if no search term is provided

    // Filter by name
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter by phone
    if ($request->has('phone') && $request->phone != '') {
        $query->where('phone', 'like', '%' . $request->phone . '%');
    }

    // Filter by category
    if ($request->has('category') && $request->category != '') {
        $query->whereHas('category', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->category . '%');
        });
    }

    // Check role-based restrictions for viewing shops
    $shopsQuery = Shop::query();

    // If the user has roles that grant access to all shops
    if ($user->hasRole('SuperAdmin') || $user->hasRole('FederalAdmin') || $user->hasRole('Admin')) {
        // No filtering by region, zone, woreda, kebele or owner
    } else {
        // Role-based filtering logic for specific roles
        if ($user->hasRole('Owners')) {
            // If the user is an 'Owner', show only their active shops
            $shopsQuery->where('owner_id', $user->id)
                       ->where('status', 'active'); // Add condition for active status
        }

        if ($user->hasRole('RegionalAdmin')) {
            // If the user is a 'RegionalAdmin', show shops in the region they manage
            $shopsQuery->whereHas('woreda.zone.region', function ($query) use ($user) {
                $query->where('region_id', $user->region_id);
            });
        }

        if ($user->hasRole('ZoneAdmin')) {
            // If the user is a 'ZoneAdmin', show shops in the zone they manage
            $shopsQuery->whereHas('woreda.zone', function ($query) use ($user) {
                $query->where('zone_id', $user->zone_id);
            });
        }

        if ($user->hasRole('WoredaAdmin')) {
            // If the user is a 'WoredaAdmin', show shops in the woreda they manage
            $shopsQuery->whereHas('woreda', function ($query) use ($user) {
                $query->where('woreda_id', $user->woreda_id);
            });
        }

        if ($user->hasRole('KebeleAdmin')) {
            // If the user is a 'KebeleAdmin', show shops in the kebele they manage
            $shopsQuery->whereHas('kebele', function ($query) use ($user) {
                $query->where('kebele_id', $user->kebele_id);
            });
        }
    }

    // Modify the query to search by multiple attributes
    $shopsQuery->when($searchTerm, function($query, $searchTerm) {
        return $query->where(function($query) use ($searchTerm) {
            $query->where('name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('phone', 'like', '%'.$searchTerm.'%')
                  ->orWhere('TIN', 'like', '%'.$searchTerm.'%')
                  ->orWhere('website', 'like', '%'.$searchTerm.'%')
                  ->orWhereHas('owner', function($query) use ($searchTerm) {
                      $query->where('name', 'like', '%'.$searchTerm.'%');
                  })
                  ->orWhereHas('kebele', function($query) use ($searchTerm) {
                      $query->where('name', 'like', '%'.$searchTerm.'%');
                  })
                  ->orWhereHas('woreda', function($query) use ($searchTerm) {
                      $query->where('name', 'like', '%'.$searchTerm.'%');
                  })
                  ->orWhereHas('zone', function($query) use ($searchTerm) {
                      $query->where('name', 'like', '%'.$searchTerm.'%');
                  })
                  ->orWhereHas('region', function($query) use ($searchTerm) {
                      $query->where('name', 'like', '%'.$searchTerm.'%');
                  });
        });
    });

    // Paginate the results
    $shops = $shopsQuery->paginate(10);

    // Return the view with all the necessary data
    return view('shops.index', compact('shops'));
}

    
    

    
public function create()
{
    // Fetch the authenticated user
    $user = Auth::user();

    // Validate the user and their role
    if (!$user) {
        return redirect()->route('login')->with('error', 'You must be logged in to create a shop.');
    }

    // Check if the user has the right role to create a shop
    if (!$user->hasRole('Super Admin') && !$user->hasRole('Admin') && !$user->hasRole('FederalAdmin') && !$user->hasRole('RegionalAdmin') && !$user->hasRole('ZoneAdmin') && !$user->hasRole('WoredaAdmin') && !$user->hasRole('KebeleAdmin')) {
        return redirect()->route('shops.index')->with('error', 'You do not have the required roles to create a shop.');
    }

    // Fetch categories
    $categories = Category::all();

    // Fetch federals based on the user being a Federaladmin
    $federals = Federal::where('admin_id', $user->id)->get();

    // Fetch regions based on the user being a Regionaladmin
    $regions = Region::where('admin_id', $user->id)->get();

    // Fetch zones based on the user being a Zoneadmin
    $zones = Zone::where('admin_id', $user->id)->get();

    // Fetch woredas based on the user being a Woredaadmin
    $woredas = Woreda::where('admin_id', $user->id)->get();

    // Fetch kebeles based on the user being a Kebeleadmin
    $kebeles = Kebele::where('admin_id', $user->id)->get();

    // Fetch owners based on the user's role
    if ($user->hasRole('Super Admin')) {
        // Super Admin has access to all owners
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->get();
    } elseif ($user->hasRole('FederalAdmin')) {
        // Federal Admin has access to owners within the federal scope
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->where('federal_id', $user->federal_id)->get(); // Assuming the user has a federal_id attribute
    } elseif ($user->hasRole('Admin')) {
        // Admin has access to owners within the same federal scope as the user
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->where('federal_id', $user->federal_id)->get(); // Assuming the user has a federal_id attribute
    } elseif ($user->hasRole('RegionalAdmin')) {
        // Regional Admin can fetch owners within the same region
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->where('region_id', $user->region_id)->get(); // Assuming the user has a region_id attribute
    } elseif ($user->hasRole('ZoneAdmin')) {
        // Zone Admin can fetch owners within the same zone
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->where('zone_id', $user->zone_id)->get(); // Assuming the user has a zone_id attribute
    } elseif ($user->hasRole('WoredaAdmin')) {
        // Woreda Admin can fetch owners within the same woreda
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->where('woreda_id', $user->woreda_id)->get(); // Assuming the user has a woreda_id attribute
    } elseif ($user->hasRole('KebeleAdmin')) {
        // Kebele Admin can fetch owners within the same kebele
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->where('kebele_id', $user->kebele_id)->get(); // Assuming the user has a kebele_id attribute
    } else {
        // Default case (if the user has no matching role)
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'Owners');
        })->get(); // Fallback for any other role
    }

    // Return the view with all the necessary data
    return view('shops.create', compact('federals', 'regions', 'zones', 'woredas', 'kebeles', 'owners', 'categories'));
}

    


    private function getOwnersByUserRole($user)
    {
        $roleName = 'Owners';
        $query = User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        });

        // Check user roles and filter owners accordingly
        if ($user->hasRole('SuperAdmin') || $user->hasRole('Admin') || $user->hasRole('FederalAdmin')) {
            return $query->get(); // Retrieve all owners
        }

        // Filtering based on user roles
        if ($user->hasRole('RegionalAdmin')) {
            return $query->where('region_id', $user->region_id)->get();
        }

        if ($user->hasRole('ZoneAdmin')) {
            return $query->where('zone_id', $user->zone_id)->get();
        }

        if ($user->hasRole('WoredaAdmin')) {
            return $query->where('woreda_id', $user->woreda_id)->get();
        }

        if ($user->hasRole('KebeleAdmin')) {
            return $query->where('kebele_id', $user->kebele_id)->get();
        }

        return collect(); // Return empty collection if no matching roles
    }











    public function show($id)
    {
        $shop = Shop::with(['owner', 'kebele.woreda.zone.region.federal'])->findOrFail($id);
        return view('shops.show', compact('shop'));
    }
    
   public function store(Request $request)
{
    // Check if the authenticated user has the role 'KebeleAdmin'
    if (!auth()->user()->hasRole('KebeleAdmin')) {
        return redirect()->route('shops.index')
            ->with('error', 'Only KebeleAdmin users are allowed to create shops.');
    }

    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'owner_id' => 'required|exists:users,id',
        'phone' => 'required|string|max:15',
        'total_capital' => 'required|numeric',
        'opening_hours' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'shop_license' => 'required|file|mimes:pdf|max:2048',
        'TIN' => 'required|numeric',
        'website' => 'required|url',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    try {
        $shop = new Shop();
        $shop->name = $request->name;
        $shop->owner_id = $request->owner_id;
        $shop->phone = $request->phone;
        $shop->total_capital = $request->total_capital;
        $shop->opening_hours = $request->opening_hours;
        $shop->category_id = $request->category_id;

        // Handle shop license file upload
        if ($request->hasFile('shop_license')) {
            try {
                $shop_license_path = $request->file('shop_license')->store('shop_licenses', 'public');
                $shop->shop_license_path = $shop_license_path;
            } catch (\Exception $e) {
                return redirect()->route('shops.index')
                    ->with('error', 'Failed to upload the shop license file: ' . $e->getMessage());
            }
        }

        // Check if user has kebele_id
        $user = Auth::user();
        if (!$user->kebele_id) {
            return redirect()->route('shops.index')
                ->with('error', 'User does not have an assigned kebele.');
        }

        // Get kebele info and set location-related fields
        $kebele = Kebele::find($user->kebele_id);
        if ($kebele) {
            $shop->kebele_id = $kebele->id;
            $shop->woreda_id = $kebele->woreda_id;
            $shop->zone_id = $kebele->woreda->zone_id;
            $shop->region_id = $kebele->woreda->zone->region_id;
            $shop->federal_id = $kebele->woreda->zone->region->federal_id;
        } else {
            return redirect()->route('shops.index')
                ->with('error', 'Invalid kebele data.');
        }

        // Set remaining fields
        $shop->TIN = $request->TIN;
        $shop->website = $request->website;
        $shop->latitude = $request->latitude;
        $shop->longitude = $request->longitude;
        $shop->status = $request->status;

        $shop->save();

        return redirect()->route('shops.index')->with('success', 'Shop created successfully.');

    } catch (\Exception $e) {
        return redirect()->route('shops.index')
            ->with('error', 'Failed to create shop: ' . $e->getMessage());
    }
}

    
    public function destroy(Shop $shop): RedirectResponse
    {
        $user = auth()->user();
    
        if (!$user->hasRole(['SuperAdmin', 'Admin']) && $shop->owner_id === $user->id) {
            return redirect()->route('shops.index')->withErrors(['error' => 'You cannot delete your own shop.']);
        }
    
        if (!$user->can('delete-shop')) {
            abort(403, 'You do not have permission to delete this shop.');
        }
    
        // Delete the shop license file if it exists
        if ($shop->shop_license_path && Storage::disk('public')->exists($shop->shop_license_path)) {
            Storage::disk('public')->delete($shop->shop_license_path);
        }
    
        // Delete the shop record
        $shop->delete();
    
        return redirect()->route('shops.index')->with('success', 'Shop deleted successfully.');
    }
    public function changeStatus(Request $request, $shopId)
{
    $shop = Shop::findOrFail($shopId);

    // Ensure the current user has the right permissions
    if (!Auth::user()->hasAnyRole(['Super Admin', 'Admin', 'FederalAdmin', 'RegionalAdmin', 'ZoneAdmin', 'WoredaAdmin', 'KebeleAdmin'])) {
        return response()->json(['success' => false, 'message' => 'You do not have permission to change the shop status.'], 403);
    }

    // Validate the new status
    $request->validate([
        'status' => 'required|in:active,inactive',
    ]);

    // Update the status
    $shop->status = $request->input('status');
        $shop->save();

        return redirect()->route('shops.index')->with('success', 'Shop Status updated successfully.');
}

public function showStatus(Shop $shop)
{
    return view('shops.show_status', compact('shop'));
}


    public function updateStatus(Request $request, $shopId)
{
    // Validate that the user has the required permission
    $this->authorize('update-status', Shop::class);

    // Find the shop or return an error response if not found
    $shop = Shop::find($shopId);
    if (!$shop) {
        return response()->json(['success' => false, 'message' => 'Shop not found'], 404);
    }

    // Update the shop's status and save it
    try {
        $shop->status = $request->input('status');
        $shop->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to update status: ' . $e->getMessage()], 500);
    }
}

    

}
