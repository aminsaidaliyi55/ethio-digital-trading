@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Supplier List</h1>

@if(auth()->user()->can('create suppliers'))
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Create Supplier</a>
@endif
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
    <tr>
        <td>{{ $supplier->name }}</td>
        @if(auth()->user()->can('edit suppliers'))
            <td><a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">Edit</a></td>
        @endif
        @if(auth()->user()->can('delete suppliers'))
            <td>
                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        @endif
    </tr>
@endforeach
        </tbody>
    </table>
</div>
@endsection
