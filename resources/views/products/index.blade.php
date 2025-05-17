@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1>@lang('messages.Product List')</h1>

    @can('create-product')
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
            @lang('messages.Add New Product')
        </a>
    @endcan

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('products.index') }}" class="mb-3">
        <div class="row">
            <!-- Search Fields -->
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="@lang('messages.Search by Name, SKU, or Status')" value="{{ request()->search }}">
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">@lang('messages.Select Category')</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request()->category == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pagination per page selection -->
            <div class="col-md-3">
                <select name="perPage" class="form-control">
                    <option value="10" {{ request()->perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="100" {{ request()->perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">@lang('messages.Search')</button>
            </div>
        </div>
    </form>

    <!-- Add products to order form -->
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <h3>@lang('messages.Select Products to Order')</h3>
        <table class="table table-bordered table-striped custom-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select-all" class="form-check-input">
                    </th>
                    <th>@lang('messages.Image')</th>
                    <th>@lang('messages.Shop')</th>
                    <th>@lang('messages.Status')</th>
                    <th>@lang('messages.Federal')</th>
                    <th>@lang('messages.Region')</th>
                    <th>@lang('messages.Zone')</th>
                    <th>@lang('messages.Woreda')</th>
                    <th>@lang('messages.Kebele')</th>
                    <th>@lang('messages.Name')</th>
                    <th>@lang('messages.SKU')</th>
                    <th>@lang('messages.Category')</th>
                    <th>@lang('messages.Stock Quantity')</th>
                    <th>@lang('messages.Selling Price')</th>
                    <th>@lang('messages.Quantity to Order')</th>
                    <th>@lang('messages.Actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_products[]" value="{{ $product->id }}" class="form-check-input">
                        </td>
                        <td>
                            @if($product->image)
<img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
     class="img-thumbnail zoom-trigger" style="width: 60px; height: auto; cursor: pointer;"
     data-bs-toggle="modal" data-bs-target="#imageModal{{ $product->id }}">

                            @else
                                <span>@lang('messages.No Image')</span>
                            @endif
                        </td>
                        <td>{{ $product->shop->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->shop->status ?? __('messages.N/A') }}</td>
                        <td>{{ $product->shop->federal->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->shop->region->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->shop->zone->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->shop->woreda->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->shop->kebele->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category->name ?? __('messages.N/A') }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>{{ number_format($product->selling_price, 2) }}</td>
                        <td>
                            <input type="number" name="quantity[{{ $product->id }}]" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control" style="width: 60px;">
                        </td>
                        <td>
                            <input type="hidden" name="shop[{{ $product->id }}][longitude]" value="{{ $product->shop->longitude ?? '' }}">
                            <input type="hidden" name="shop[{{ $product->id }}][latitude]" value="{{ $product->shop->latitude ?? '' }}">

                            <a href="{{ route('shops.show', $product->shop->id) }}" class="btn btn-info btn-sm">@lang('messages.Location')</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">@lang('messages.Edit')</a>
                        </td>
                    </tr>
                    <!-- Image Modal -->
<div class="modal fade" id="imageModal{{ $product->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $product->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel{{ $product->id }}">{{ $product->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('messages.Close')"></button>
      </div>
      <div class="modal-body text-center">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
      </div>
    </div>
  </div>
</div>

                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">@lang('messages.Create Order')</button>
    </form>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
</div>

<!-- Styles and scripts unchanged -->
     @push('styles')
<style>
    .zoomable {
        width: 60px;
        height: auto;
        transition: transform 0.2s ease;
        cursor: zoom-in;
    }
    .zoomable:hover {
        transform: scale(2.5);
        z-index: 9999;
        position: relative;
    }

    .zoomable {
        width: 60px;
        height: auto;
        transition: transform 0.2s ease;
        cursor: zoom-in;
    }

    .zoomable:hover {
        transform: scale(2.5);
        z-index: 9999;
        position: relative;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Optional: style scrollbars in WebKit browsers */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

@endpush


@endsection
