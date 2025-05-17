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
        height: 100vh; /* Full viewport height */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 20px; /* Add padding for aesthetics */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
    }
</style>

<div class="scrollable-container"> <!-- Use the new class for scrollable properties -->
    <h1 class="mb-4">Region Details</h1>

    <!-- Display the region's details -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>{{ $region->name }}</h2> <!-- Region name -->
        </div>
        <div class="card-body">
            <p><strong>Region Name:</strong> {{ $region->name ?? 'N/A' }}</p> <!-- Display region name -->
        </div>
    </div>

    <!-- Search and Sort Form -->
    <div class="mb-4">
        <form method="GET" action="{{ route('regions.show', $region->id) }}" class="form-inline">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control" placeholder="Search Zones" value="{{ request('search') }}">
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

    <!-- Display the zones associated with the region in a paginated table -->
    <h3>Zones in this Region</h3>
    @if($zones->isEmpty())
        <p>No zones found for this region.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Zone Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($zones as $index => $zone) <!-- Use the paginated zones variable -->
                    <tr>
                        <td>{{ $zones->firstItem() + $index }}</td> <!-- Display listing number -->
                        <td>{{ $zone->name }}</td> <!-- Display zone name -->
                        <td>
                            <a href="{{ route('zones.show', $zone->id) }}" class="btn btn-info btn-sm">View</a>

                            <!-- Check if user's region_id matches zone's region_id -->
                            @if (Auth::user()->region_id === $zone->region_id) <!-- Use the zone's region_id here -->

                            @can('zones-edit')
                                <a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            @endcan

                            @can('zones-delete') <!-- Adjust the permission name if necessary -->
                                <form action="{{ route('zones.destroy', $zone->id) }}" method="post" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this zone?');">Delete</button>
                                </form>
                            @endcan

                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination links -->
        {{ $zones->links() }} <!-- Use the paginated zones variable -->
    @endif

    <!-- Back button -->
    <a href="{{ route('regions.index') }}" class="btn btn-secondary">Back to Regions</a>
</div>
@endsection
