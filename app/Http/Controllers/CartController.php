<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add a product to the cart.
     */
    public function create($productId)
{
    $product = Product::findOrFail($productId);
    return view('carts.create', compact('product'));
}
    public function addToCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);
        $customerId = Auth::user()->id; // Assuming you are using Auth to get the customer ID

        $cartItem = Cart::where('customer_id', $customerId)
                        ->where('product_id', $productId)
                        ->first();

        if ($cartItem) {
            // If the product is already in the cart, update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Otherwise, create a new cart item
            Cart::create([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Display the customer's cart.
     */
    public function index()
    {
        $customerId = Auth::user()->id;
        $carts = Cart::with('product')->where('customer_id', $customerId)->get();

        return view('carts.index', compact('carts'));
    }
    public function edit($id)
{
    $cartItem = CartItem::findOrFail($id); // Adjust this to your cart item model
    return view('carts.edit', compact('cartItem'));
}
public function show($id)
{
    $cartItem = CartItem::findOrFail($id); // Adjust this to your cart item model
    return view('carts.show', compact('cartItem'));
}
public function destroy($id)
{
    // Find the cart item by ID and delete it
    $cartItem = CartItem::findOrFail($id);
    $cartItem->delete();

    // Redirect back to the cart index with a success message
    return redirect()->route('cart.index')->with('success', 'Cart item removed successfully.');
}

}
