<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\NewOrderNotification;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Orders::query();

        // Role-based filters
        if ($user->hasRole('Owners')) {
            $query->whereHas('product.shop', fn($q) => $q->where('owner_id', $user->id));
        } elseif ($user->hasRole('WoredaAdmin')) {
            $query->whereHas('product.shop.owner', fn($q) => $q->where('woreda_id', $user->woreda_id));
        } elseif ($user->hasRole('KebeleAdmin')) {
            $query->whereHas('product.shop.owner', fn($q) => $q->where('kebele_id', $user->kebele_id));
        } elseif ($user->hasRole('ZoneAdmin')) {
            $query->whereHas('product.shop.owner', fn($q) => $q->where('zone_id', $user->zone_id));
        } elseif ($user->hasRole('RegionalAdmin')) {
            $query->whereHas('product.shop.owner', fn($q) => $q->where('region_id', $user->region_id));
        } elseif ($user->hasRole('Customer')) {
            $query->where('user_id', $user->id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%"))
                  ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('product.shop.kebele', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('product.shop.woreda', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('product.shop.zone', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('product.shop.region', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        // Prioritize pending orders
        $query->orderByRaw("CASE 
            WHEN status = 'pending' THEN 1 
            WHEN status = 'completed' THEN 2 
            WHEN status = 'cancelled' THEN 3 
            ELSE 4 END");

        $perPage = $request->get('perPage', 10);

        $orders = $query->with(['approvedBy', 'product.shop'])->paginate($perPage);
        $totalQuantity = $orders->sum('quantity');

        return view('order.index', compact('orders', 'totalQuantity'));
    }

    /**
     * Store new orders.
     */
    public function store(Request $request)
    {
        $request->validate([
            'selected_products' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $groupedOrders = [];

            foreach ($request->selected_products as $productId) {
                $product = Product::with('shop.kebele.woreda.zone.region', 'shop.owner')->findOrFail($productId);
                $quantity = $request->quantity[$productId] ?? 0;

                if ($product->stock_quantity < $quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock_quantity}");
                }

                $key = "{$product->shop->id}|{$product->id}|pending";

                $groupedOrders[$key] = $groupedOrders[$key] ?? [
                    'shop' => $product->shop,
                    'product' => $product,
                    'quantity' => 0,
                    'total_price' => 0,
                ];

                $groupedOrders[$key]['quantity'] += $quantity;
                $groupedOrders[$key]['total_price'] += $product->selling_price * $quantity;
            }


foreach ($groupedOrders as $group) {
    $product = $group['product'];
    $shop = $group['shop'];

    $existing = Orders::where('status', 'pending')
        ->where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->whereHas('product.shop', fn($q) => $q->where('id', $shop->id))
        ->first();

    if ($existing) {
        $existing->quantity += $group['quantity'];
        $existing->total_price += $group['total_price'];
        $existing->save();

        $order = $existing;
    } else {
        $order = Orders::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'status' => 'pending',
            'quantity' => $group['quantity'],
            'total_price' => $group['total_price'],
            'approved_by' => null,
        ]);
    }

    // ✅ Notify shop owner
    $owner = $shop->owner;
    if ($owner && $owner->hasRole('Owners')) {
        $owner->notify(new \App\Notifications\NewOrderNotification($order));
    }
}

    // ✅ Send notification to shop owner

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Order(s) processed successfully!');
        } 
        
        catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Order creation failed', ['user_id' => auth()->id(), 'message' => $e->getMessage()]);

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Approve or complete an order.
     */
    public function approveOrder(Request $request, Orders $order)
    {
        $user = auth()->user();
        $product = $order->product;

        if (!$product) {
            return redirect()->back()->with('error', 'Order product not found.');
        }

        // Role-based permission check
        $owner = $product->shop->owner;

        if (
            ($user->hasRole('RegionalAdmin') && $owner->region_id !== $user->region_id) ||
            ($user->hasRole('ZoneAdmin') && $owner->zone_id !== $user->zone_id) ||
            ($user->hasRole('WoredaAdmin') && $owner->woreda_id !== $user->woreda_id) ||
            ($user->hasRole('KebeleAdmin') && $owner->kebele_id !== $user->kebele_id) ||
            ($user->hasRole('Owners') && $owner->id !== $user->id)
        ) {
            return redirect()->back()->with('error', 'Unauthorized to approve this order.');
        }

        $newStatus = $request->input('status');

        if ($order->status === 'completed') {
            return redirect()->back()->with('error', 'Order already completed.');
        }

        if ($newStatus === 'completed') {
            if ($product->stock_quantity < $order->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock.');
            }

            $product->stock_quantity -= $order->quantity;
            $product->save();

            $order->approved_by = $user->id;
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order status updated.');
    }

    /**
     * Show the order receipt without QR code.
     */
    public function showReceipt($orderId)
    {
        $order = Orders::with(['product.shop.owner', 'approvedBy', 'product.shop.kebele.woreda.zone.region'])
                      ->findOrFail($orderId);

        return view('orders.order_receipt', compact('order'));
    }
}
