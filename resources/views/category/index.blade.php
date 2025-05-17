@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Category List</div>
    <div class="card-body">
        @can('create-category')
            <a href="{{ route('category.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Category</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Tax (%)</th> <!-- Added Tax Column -->
                    @can('edit-category')
                        <th scope="col">Action</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->tax ?? 'N/A' }}</td> <!-- Display Tax Value -->

                    <td>
                        <form action="{{ route('category.destroy', $category->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            @can('view-category')
                                <a href="{{ route('category.show', $category->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>
                            @endcan

                            @can('edit-category')
                                <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-product')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this category?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <span class="text-danger">
                                <strong>No Categories Found!</strong>
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
