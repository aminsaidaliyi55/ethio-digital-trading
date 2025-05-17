@extends('layouts.app') <!-- Extending a base layout -->

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
    <h1 class="mb-4">Federals</h1>

    @can('federals-create') <!-- Check if user has permission to create a federal -->
    <a href="{{ route('federals.create') }}" class="btn btn-primary mb-3">Create Federal</a>
    @endcan

    @if(session('success')) <!-- Show success message if available -->
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('federals.index') }}" method="GET" class="mb-3">
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
                    <a href="{{ route('federals.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        ID
                        @if($sortField === 'id')
                            <span class="small">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('federals.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
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
            @forelse($federals as $federal) <!-- Loop through federals -->
                <tr>
                    <td>{{ $federal->id }}</td>
                    <td>{{ $federal->name }}</td>
                    <td>
                        {{ $federal->admin->name ?? 'No Admin Assigned' }} <!-- Display current admin -->
                    </td>
                    <td>
                        @can('federals-view') <!-- Check if user has permission to view -->
                            <a href="{{ route('federals.show', $federal->id) }}" class="btn btn-info">View</a>
                        @endcan
                        @can('federals-edit') <!-- Check if user has permission to edit -->
                            <a href="{{ route('federals.edit', $federal->id) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        @can('federals-delete') <!-- Check if user has permission to delete -->
                            <form action="{{ route('federals.destroy', $federal->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this federal?')">Delete</button>
                            </form>
                        @endcan
                        
                        <!-- Assign Admin Button -->
                        @can('federals-assign-admin') <!-- Assuming there's permission to assign admin -->
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignAdminModal{{ $federal->id }}">
                            Assign Admin
                        </button>
                        @endcan
                    </td>
                </tr>

                <!-- Assign Admin Modal -->
                <div class="modal fade" id="assignAdminModal{{ $federal->id }}" tabindex="-1" aria-labelledby="assignAdminModalLabel{{ $federal->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignAdminModalLabel{{ $federal->id }}">Assign Admin for {{ $federal->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                
                            
                            
                            <form action="{{ route('federals.assignadmin') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="federal_id">Federal</label>
        <select name="federal_id" id="federal_id" class="form-control" required>
            @foreach($federals as $federal)
                <option value="{{ $federal->id }}">{{ $federal->name }}</option>
            @endforeach
        </select>
    </div>

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
            @empty <!-- Show message if no federals are found -->
                <tr>
                    <td colspan="4" class="text-center">No federals found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $federals->links() }} <!-- Pagination links -->
</div>
@endsection
