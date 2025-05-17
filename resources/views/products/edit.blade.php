@extends('layouts.app')

@section('content')
<div class="container scrollable-container">
    <h1>Edit Product: {{ $product->name }}</h1>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back to Product List</a>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Add this to specify the update method -->

        <div class="row mb-3">
            <label for="shop_id" class="form-label">Shop</label>
            <select name="shop_id" id="shop_id" class="form-select" required>
                @foreach ($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
            @error('shop_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input type="text" class="form-control" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required>
                @error('sku')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required onchange="fetchTax()">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" data-tax="{{ $category->tax }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                @if($product->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                @endif
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="stock_in" class="form-label">Stock In</label>
                <input type="number" class="form-control" name="stock_in" id="stock_in" value="{{ old('stock_in', $product->stock_in) }}" required oninput="updateStockQuantity()">
                @error('stock_in')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="stock_out" class="form-label">Stock Out</label>
                <input type="number" class="form-control" name="stock_out" id="stock_out" value="{{ old('stock_out', $product->stock_out) }}" required oninput="updateStockQuantity()">
                @error('stock_out')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="stock_quantity" class="form-label">Total Stock Quantity</label>
                <input type="number" class="form-control" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="purchased_price" class="form-label">Purchased Price</label>
                <input type="number" class="form-control" name="purchased_price" id="purchased_price" step="0.01" min="0" value="{{ old('purchased_price', $product->purchased_price) }}" required oninput="calculateProfit()">
                @error('purchased_price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="selling_price" class="form-label">Selling Price</label>
                <input type="number" class="form-control" name="selling_price" id="selling_price" step="0.01" min="0" value="{{ old('selling_price', $product->selling_price) }}" required oninput="calculateTotal()">
                @error('selling_price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="tax" class="form-label">Tax (%)</label>
                <input type="number" class="form-control" name="tax" id="tax" step="0.01" min="0" value="{{ old('tax', $product->tax) }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="total_price" class="form-label">Total Price</label>
                <input type="number" class="form-control" name="total_price" id="total_price" value="{{ old('total_price', $product->total_price) }}" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="profit" class="form-label">Profit</label>
                <input type="number" class="form-control" name="profit" id="profit" value="{{ old('profit', $product->profit) }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label for="profit_percent" class="form-label">Profit in Percent</label>
                <input type="number" class="form-control" name="profit_percent" id="profit_percent" value="{{ old('profit_percent', $product->profit_percent) }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <input type="date" class="form-control" name="stock_in_date" id="stock_in_date" value="{{ old('stock_in_date', $product->stock_in_date) }}" required hidden>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Set today's date for stock_in_date input if not provided
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        
        // Check if stock_in_date is empty
        const stockInDateInput = document.getElementById('stock_in_date');
        if (!stockInDateInput.value) {
            stockInDateInput.value = `${year}-${month}-${day}`;
        }

        // Set initial tax value from the selected category
        fetchTax();
    });

    function updateStockQuantity() {
        const stockIn = parseInt(document.getElementById('stock_in').value) || 0;
        const stockOut = parseInt(document.getElementById('stock_out').value) || 0;
        document.getElementById('stock_quantity').value = stockIn + stockOut;

        // Recalculate total price when stock is updated
        calculateTotal();
    }

    function calculateProfit() {
        const purchasedPrice = parseFloat(document.getElementById('purchased_price').value) || 0;
        const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
        const profit = sellingPrice - purchasedPrice;

        document.getElementById('profit').value = profit;
        calculateProfitPercent();
    }

    function calculateProfitPercent() {
        const purchasedPrice = parseFloat(document.getElementById('purchased_price').value) || 0;
        const profit = parseFloat(document.getElementById('profit').value) || 0;
        const profitPercent = (profit / purchasedPrice) * 100;

        document.getElementById('profit_percent').value = profitPercent || 0;
    }

    function calculateTotal() {
        const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        const total = sellingPrice + (sellingPrice * tax / 100);

        document.getElementById('total_price').value = total;
    }

    function fetchTax() {
        const selectedCategory = document.getElementById('category_id');
        const selectedOption = selectedCategory.options[selectedCategory.selectedIndex];
        const tax = selectedOption.getAttribute('data-tax');
        
        document.getElementById('tax').value = tax;
        calculateTotal(); // Recalculate total when category changes
    }
</script>
<style>
.scrollable-container {
    max-height: 80vh;
    overflow-y: auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.text-danger {
    font-size: 0.875em;
    margin-top: 0.25rem;
}
</style>
@endsection
