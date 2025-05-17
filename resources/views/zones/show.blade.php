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
    <h1 class="mb-4">Zone Details</h1>

    <!-- Display the zone's details -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $zone->name }}</h2> <!-- Zone name -->
        </div>
        <div class="card-body">
            <p><strong>Zone Name:</strong> {{ $zone->name ?? 'N/A' }}</p> <!-- Display zone name -->
        </div>
    </div>

    <!-- Search and Sort Form -->
    <div class="mb-4">
        <form method="GET" action="{{ route('zones.show', $zone->id) }}" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Search Woredas" value="{{ $search }}">
            </div>
            <div class="form-group mr-2">
                <select name="sort" class="form-control">
                    <option value="name" {{ $sortField === 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="created_at" {{ $sortField === 'created_at' ? 'selected' : '' }}>Sort by Created Date</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <select name="direction" class="form-control">
                    <option value="asc" {{ $sortDirection === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ $sortDirection === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Display the Woredas associated with the Zone in a table -->
    <h3>Woredas in this Zone</h3>
    @if($woredas->isEmpty())
        <p>No Woreda found for this Zone.</p>
    @else
        <table class="table table-striped table-bordered mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Woreda Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    @foreach($woredas as $index => $woreda) <!-- Use the paginated woredas variable -->
        <tr>
            <td>{{ $woredas->firstItem() + $index }}</td> <!-- Display listing number -->
            <td>{{ $woreda->name }}</td> <!-- Display Woreda name -->
            <td>
                <a href="{{ route('woredas.show', $woreda->id) }}" class="btn btn-info btn-sm">View</a>
                
                <!-- Check if user's region_id matches zone's region_id -->
            @if (Auth::user()->region_id === $woreda->zone->region_id) <!-- Use the zone's region_id here -->

                @can('woredas-edit')

                <a href="{{ route('woredas.edit', $woreda->id) }}" class="btn btn-primary btn-sm">Edit</a>

                <form action="{{ route('woredas.destroy', $woreda->id) }}" method="post" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Woreda?');">Delete</button>
                </form>
                   @endcan

                            @endif
            </td>
        </tr>
    @endforeach
</tbody>

        <!-- Pagination links -->
        {{ $woredas->links() }} <!-- Use the paginated woredas variable -->
    @endif

    <!-- Back button -->
    <a href="{{ route('zones.index') }}" class="btn btn-secondary">Back to Zones</a>
</div>
@endsection
