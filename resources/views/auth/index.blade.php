@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">Manage Users</div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="{{ request()->query('search') }}" class="form-control" placeholder="Search users...">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
            </div>
        </form>

        @can('create-user')
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New User</a>
        @endcan

        <!-- Import/Export Buttons -->
        <div class="mb-3">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls" required>
                <button type="submit" class="btn btn-info btn-sm"><i class="bi bi-upload"></i> Import Users</button>
            </form>
            <a href="{{ route('users.export') }}" class="btn btn-secondary btn-sm"><i class="bi bi-download"></i> Export Users</a>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">S#</a></th>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'name', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Name</a></th>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'email', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Email</a></th>
                    <th scope="col">Roles</th>
                    <th scope="col">Admin/User</th>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'region_id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Region</a></th>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'zone_id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Zone</a></th>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'woreda_id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Woreda</a></th>
                    <th scope="col"><a href="{{ route('users.index', ['sort_by' => 'kebele_id', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Kebele</a></th>
                    
                    @canany(['edit-user', 'delete-user'])
                        <th scope="col">Action</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse ($user->getRoleNames() as $role)
                                <span class="badge bg-primary">{{ $role }}</span>
                            @empty
                                <span class="text-muted">No roles</span>
                            @endforelse
                        </td>
                        <td>
                            @if($user->hasRole(['Super Admin', 'Admin', 'FederalAdmin', 'RegionalAdmin', 'ZoneAdmin', 'WoredaAdmin', 'KebeleAdmin']))
                                <span class="text-muted">Admin</span>
                            @else
                                <span class="text-muted">User</span>
                            @endif
                        </td>
                        <td>{{ $user->region->name ?? 'All Region' }}</td>
                        <td>{{ $user->zone->name ?? 'All Zone' }}</td>
                        <td>{{ $user->woreda->name ?? 'All Woreda' }}</td>
                        <td>{{ $user->kebele->name ?? 'All Kebele' }}</td>
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-eye"></i> Show
                                </a>

                                @if (Auth::user()->getRoleLevel() <= $user->getRoleLevel()) {{-- Ensure role levels are respected --}}
                                    @can('edit-user')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    @endcan

                                    @can('delete-user')
                                        @if (Auth::user()->id != $user->id) {{-- Prevent self-deletion --}}
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this user?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @endif
                                    @endcan
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            <span class="text-danger">
                                <strong>No Users Found!</strong>
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>

@endsection
