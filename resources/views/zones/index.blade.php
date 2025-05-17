@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <h1 class="mb-4">Zones</h1>

    @can('zones-create')
        <a href="{{ route('zones.create') }}" class="btn btn-primary mb-3">Create Zone</a>
    @endcan

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('zones.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <!-- Back Button -->
    <a href="{{ route('home') }}" class="btn btn-secondary mb-3">Back</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    <a href="{{ route('zones.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        ID
                        @if($sortField === 'id')
                            <span class="small">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('zones.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        Name
                        @if($sortField === 'name')
                            <span class="small">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th>Assigned Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($zones as $zone)
                <tr>
                    <td>{{ $zone->id }}</td>
                    <td>{{ $zone->name }}</td>
                    <td>{{ $zone->admin->name ?? 'No Admin Assigned' }}</td>
                    <td>
                        @can('zones-view')
                            <a href="{{ route('zones.show', $zone->id) }}" class="btn btn-info">View</a>
                        @endcan
                        @can('zones-edit')
                            <a href="{{ route('zones.edit', $zone->id) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        @can('zones-delete')
                            <form action="{{ route('zones.destroy', $zone->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this zone?')">Delete</button>
                            </form>
                        @endcan
                        
                        <!-- Assign Admin Button -->
                        @can('zones-create')
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignAdminModal{{ $zone->id }}">
                                Assign Admin
                            </button>
                        @endcan
                    </td>
                </tr>

                <!-- Assign Admin Modal -->
                <div class="modal fade" id="assignAdminModal{{ $zone->id }}" tabindex="-1" aria-labelledby="assignAdminModalLabel{{ $zone->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignAdminModalLabel{{ $zone->id }}">Assign Admin for {{ $zone->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('zones.assignAdmin', ['zone' => $zone->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="zone_id" value="{{ $zone->id }}">
                                    <div class="form-group">
                                        <label for="admin_id">Admin</label>
                                        <select name="admin_id" id="admin_id" class="form-control" required>
                                            @foreach($admins as $admin)
                                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Assign Admin</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No zones found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $zones->appends(request()->except('page'))->links() }} <!-- Pagination links with search and sort persistence -->
</div>

@endsection
