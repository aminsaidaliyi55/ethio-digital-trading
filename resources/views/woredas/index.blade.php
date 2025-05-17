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
    <h1 class="mb-4">Woredas</h1>

    @can('woredas-create')
        <a href="{{ route('woredas.create') }}" class="btn btn-primary mb-3">Create Woreda</a>
    @endcan

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('woredas.index') }}" method="GET" class="mb-3">
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
                    <a href="{{ route('woredas.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        ID
                        @if($sortField === 'id')
                            <span class="small">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('woredas.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
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
            @forelse($woredas as $woreda)
                <tr>
                    <td>{{ $woreda->id }}</td>
                    <td>{{ $woreda->name }}</td>
                    <td>{{ $woreda->admin->name ?? 'No Admin Assigned' }}</td>
                    <td>
                        @can('woredas-view')
                            <a href="{{ route('woredas.show', $woreda->id) }}" class="btn btn-info">View</a>
                        @endcan
                        @can('woredas-edit')
                            <a href="{{ route('woredas.edit', $woreda->id) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        @can('woredas-delete')
                            <form action="{{ route('woredas.destroy', $woreda->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this woreda?')">Delete</button>
                            </form>
                        @endcan
                        
                        <!-- Assign Admin Button -->
                        @can('woredas-create')
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignAdminModal{{ $woreda->id }}">
                            Assign Admin
                        </button>
                        @endcan
                    </td>
                </tr>

                <!-- Assign Admin Modal -->
                <div class="modal fade" id="assignAdminModal{{ $woreda->id }}" tabindex="-1" aria-labelledby="assignAdminModalLabel{{ $woreda->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignAdminModalLabel{{ $woreda->id }}">Assign Admin for {{ $woreda->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('woredas.assignAdmin', ['woreda' => $woreda->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="woreda_id" value="{{ $woreda->id }}">
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
                    <td colspan="4" class="text-center">No woredas found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $woredas->links() }} <!-- Pagination links -->
</div>
@endsection
