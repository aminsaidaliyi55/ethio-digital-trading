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
    <h1 class="mb-4">kebeles</h1>

    @can('kebeles-create')
        <a href="{{ route('kebeles.create') }}" class="btn btn-primary mb-3">Create kebele</a>
    @endcan

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form action="{{ route('kebeles.index') }}" method="GET" class="mb-3">
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
                    <a href="{{ route('kebeles.index', ['sort_field' => 'id', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                        ID
                        @if($sortField === 'id')
                            <span class="small">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('kebeles.index', ['sort_field' => 'name', 'sort_direction' => $sortDirection === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
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
            @forelse($kebeles as $kebele)
                <tr>
                    <td>{{ $kebele->id }}</td>
                    <td>{{ $kebele->name }}</td>
                    <td>{{ $kebele->admin->name ?? 'No Admin Assigned' }}</td>
                    <td>
                       
                        @can('kebeles-edit')
                            <a href="{{ route('kebeles.edit', $kebele->id) }}" class="btn btn-warning">Edit</a>
                        @endcan
                        @can('kebeles-delete')
                            <form action="{{ route('kebeles.destroy', $kebele->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this kebele?')">Delete</button>
                            </form>
                        @endcan
                        
                        <!-- Assign Admin Button -->
                        @can('kebeles-create')
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#assignAdminModal{{ $kebele->id }}">
                            Assign Admin
                        </button>
                        @endcan
                    </td>
                </tr>

                <!-- Assign Admin Modal -->
                <div class="modal fade" id="assignAdminModal{{ $kebele->id }}" tabindex="-1" aria-labelledby="assignAdminModalLabel{{ $kebele->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignAdminModalLabel{{ $kebele->id }}">Assign Admin for {{ $kebele->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('kebeles.assignAdmin', ['kebele' => $kebele->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="kebele_id" value="{{ $kebele->id }}">
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
                    <td colspan="4" class="text-center">No kebeles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $kebeles->links() }} <!-- Pagination links -->
</div>
@endsection
