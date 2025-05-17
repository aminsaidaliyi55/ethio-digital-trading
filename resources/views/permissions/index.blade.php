@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        @lang('messages.permission_list')
        <div class="float-end">
            <!-- Search Form -->
            <form action="{{ route('permissions.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="@lang('messages.search_permissions')" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">@lang('messages.search')</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @can('create-permission')
            <a href="{{ route('permissions.create') }}" class="btn btn-success btn-sm my-2">
                <i class="bi bi-plus-circle"></i> @lang('messages.add_new_permission')
            </a>
        @endcan

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">@lang('messages.serial_number')</th>
                    <th scope="col">
                        <!-- Sort by Name -->
                        <a href="{{ route('permissions.index', ['sort' => 'name', 'order' => $sortField === 'name' && $sortOrder === 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">
                            @lang('messages.name')
                            @if ($sortField === 'name')
                                <i class="bi bi-arrow-{{ $sortOrder === 'asc' ? 'down' : 'up' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th scope="col">@lang('messages.action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $permission)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('view-permission')
                                <a href="{{ route('permissions.show', $permission->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-eye"></i> @lang('messages.show')
                                </a>
                            @endcan

                            @can('edit-permission')
                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> @lang('messages.edit')
                                </a>
                            @endcan

                            @can('delete-permission')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('@lang('messages.confirm_delete_permission')');">
                                    <i class="bi bi-trash"></i> @lang('messages.delete')
                                </button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            <strong>@lang('messages.no_permissions_found')</strong>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $permissions->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
