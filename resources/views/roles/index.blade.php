@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">@lang('messages.manage_roles')</div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" action="{{ route('roles.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="{{ request()->query('search') }}" class="form-control" placeholder="@lang('messages.search_roles')">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> @lang('messages.search')</button>
            </div>
        </form>

        @can('create-role')
            <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm my-2">
                <i class="bi bi-plus-circle"></i> @lang('messages.add_new_role')
            </a>
        @endcan

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">@lang('messages.serial_number')</th>
                    <th scope="col">@lang('messages.name')</th>
                    <th scope="col" style="width: 250px;">@lang('messages.action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $role->name }}</td>
                    <td>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-eye"></i> @lang('messages.show')
                            </a>

                            @if ($role->name != 'Super Admin')
                                @can('edit-role')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil-square"></i> @lang('messages.edit')
                                    </a>
                                @endcan

                                @can('delete-role')
                                    @if ($role->name != Auth::user()->hasRole($role->name))
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('@lang('messages.confirm_delete_role')');">
                                            <i class="bi bi-trash"></i> @lang('messages.delete')
                                        </button>
                                    @endif
                                @endcan
                            @endif

                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <span class="text-danger">
                                <strong>@lang('messages.no_role_found')</strong>
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $roles->links() }}

    </div>
</div>
@endsection
