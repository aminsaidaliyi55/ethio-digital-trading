@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">@lang('messages.Manage Users')</div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="{{ request()->query('search') }}" class="form-control" placeholder="@lang('messages.Search users...')">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> @lang('messages.Search')
                </button>
            </div>
        </form>

        @can('create-user')
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm my-2">
                <i class="bi bi-plus-circle"></i> @lang('messages.Add New User')
            </a>
        @endcan

        <!-- Display Total Users -->
        <div class="alert alert-info">
            @if(request()->query('search'))
                <strong>{{ $users->total() }}</strong> @lang('messages.users found for search term'): <strong>"{{ request()->query('search') }}"</strong>
            @else
                @lang('messages.Total users'): <strong>{{ $users->total() }}</strong>
            @endif
        </div>

        <!-- Import/Export Buttons -->
        <div class="mb-3">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls" required>
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="bi bi-upload"></i> @lang('messages.Import Users')
                </button>
            </form>
            <a href="{{ route('users.export') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-download"></i> @lang('messages.Export Users')
            </a>
        </div>

        <!-- User Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    @php
                        // To keep existing query parameters except sort_by and sort_direction
                        $query = request()->query();
                        $toggleDirection = (request('sort_direction') === 'asc') ? 'desc' : 'asc';
                    @endphp

                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'id', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.#')
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'name', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.Name')
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'email', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.Email')
                        </a>
                    </th>
                    <th scope="col">@lang('messages.Roles')</th>
                    <th scope="col">@lang('messages.Admin/User')</th>
                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'region_id', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.Region')
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'zone_id', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.Zone')
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'woreda_id', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.Woreda')
                        </a>
                    </th>
                    <th scope="col">
                        <a href="{{ route('users.index', array_merge($query, ['sort_by' => 'kebele_id', 'sort_direction' => $toggleDirection])) }}">
                            @lang('messages.Kebele')
                        </a>
                    </th>

                    @canany(['edit-user', 'delete-user'])
                        <th scope="col">@lang('messages.Action')</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <th scope="row">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse ($user->getRoleNames() as $role)
                                <span class="badge bg-primary">{{ $role }}</span>
                            @empty
                                <span class="text-muted">@lang('messages.Has No Roles')</span>
                            @endforelse
                        </td>
                        <td>
                            @if($user->hasAnyRole(['Super Admin', 'Admin', 'FederalAdmin', 'RegionalAdmin', 'ZoneAdmin', 'WoredaAdmin', 'KebeleAdmin']))
                                <span class="text-muted">@lang('messages.Admin')</span>
                            @else
                                <span class="text-muted">@lang('messages.User')</span>
                            @endif
                        </td>
                        <td>{{ $user->region->name ?? __('messages.No Region Assigned') }}</td>
                        <td>{{ $user->zone->name ?? __('messages.No Zone Assigned') }}</td>
                        <td>{{ $user->woreda->name ?? __('messages.No Woreda Assigned') }}</td>
                        <td>{{ $user->kebele->name ?? __('messages.No Kebele Assigned') }}</td>

                        @canany(['edit-user', 'delete-user'])
                            <td>
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-sm mb-1">
                                    <i class="bi bi-eye"></i> @lang('messages.Show')
                                </a>

                                @if (Auth::user()->getRoleLevel() <= $user->getRoleLevel())
                                    @can('edit-user')
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm mb-1">
                                            <i class="bi bi-pencil-square"></i> @lang('messages.Edit')
                                        </a>
                                    @endcan

                                    @can('delete-user')
                                        @if (Auth::id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('@lang('messages.Do you want to delete this user?')');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> @lang('messages.Delete')
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                @endif
                            </td>
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ (Gate::allows('edit-user') || Gate::allows('delete-user')) ? 10 : 9 }}" class="text-center text-danger">
                            <strong>@lang('messages.No Users Found!')</strong>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->appends(request()->query())->links() }}
    </div>
</div>

@endsection
