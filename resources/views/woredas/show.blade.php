@extends('layouts.app') <!-- Extending the base layout -->

@section('content')
<style>
    /* Add CSS for scrollable page */
    html, body {
        height: 200%; /* Ensure full height */
        margin: 0; /* Remove default margins */
        overflow: auto; /* Hide default overflow */
    }

    .scrollable-container {
        max-height: 100vh; /* Limit height to viewport height */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 20px; /* Add padding for aesthetics */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
    }
</style>

<div class="scrollable-container"> <!-- Use the new class for scrollable properties -->
    <h1 class="mb-4">Woreda Details</h1>

    <!-- Display the woreda's details -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $woreda->name }}</h2> <!-- Woreda name -->
        </div>
        <div class="card-body">
            <p><strong>Woreda Name:</strong> {{ $woreda->name ?? 'N/A' }}</p> <!-- Display woreda name -->
        </div>
    </div>

    <!-- New Shop Button -->
    <div class="mb-4">
        <a href="{{ route('shops.create') }}" class="btn btn-success">New Shop</a>
    </div>

    <!-- Search and Sort Form -->
    <div class="mb-4">
        <form method="GET" action="{{ route('woredas.show', $woreda->id) }}" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Search Kebeles" value="{{ request('search') }}">
            </div>
            <div class="form-group mr-2">
                <select name="sort" class="form-control">
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Sort by Created Date</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <select name="direction" class="form-control">
                    <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Display the Kebeles associated with the Woreda in a table -->
    <h3>Kebeles in this Woreda</h3>
    @if($kebeles->isEmpty())
        <p>No Kebele found for this Woreda.</p>
    @else
        <table class="table table-striped table-bordered mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kebele Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kebeles as $index => $kebele) <!-- Use the paginated kebeles variable -->
                    <tr>
                        <td>{{ $kebeles->firstItem() + $index }}</td> <!-- Display listing number -->
                        <td>{{ $kebele->name }}</td> <!-- Display Kebele name -->
                        <td>
                            <a href="{{ route('kebeles.show', $kebele->id) }}" class="btn btn-info btn-sm">View</a>

                                <!-- Add other fields as needed -->
                @if (Auth::user()->region_id === $kebele->woreda->zone->region_id) <!-- Check if user's region_id matches kebele's region_id -->

            @can('kebeles-edit') 
                            <a href="{{ route('kebeles.edit', $kebele->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            <form action="{{ route('kebeles.destroy', $kebele->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Kebele?');">Delete</button>
                            </form>
                                @endcan
                                          @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        {{ $kebeles->links() }} <!-- Use the paginated kebeles variable -->
    @endif

    <!-- Back button -->
    <a href="{{ route('woredas.index') }}" class="btn btn-secondary">Back to Woredas</a>
</div>
@endsection
