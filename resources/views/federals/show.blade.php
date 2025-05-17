@extends('layouts.app') <!-- Extending a base layout -->

@section('content')
<div class="container">
    <h1 class="mb-4">Federal Details</h1>

    @if(session('success')) <!-- Show success message if available -->
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            Federal Information
        </div>
        <div class="card-body">
            <h5 class="card-title">ID: {{ $federal->id }}</h5>
            <p class="card-text"><strong>Name:</strong> {{ $federal->name }}</p>
        </div>
    </div>

    <div class="mt-4">
        @can('federals-edit') <!-- Check if user has permission to edit -->
            <a href="{{ route('federals.edit', $federal->id) }}" class="btn btn-warning">Edit Federal</a>
        @endcan
        
        @can('federals-delete') <!-- Check if user has permission to delete -->
            <form action="{{ route('federals.destroy', $federal->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this federal?')">Delete Federal</button>
            </form>
        @endcan
<br><br>
        <!-- List Regions -->
        <h3>Regions in this Federal</h3>
        @if($federal->regions->isEmpty())
            <p>No regions found for this federal.</p>
        @else
            <ul class="list-group mb-4">
                @foreach($federal->regions as $region)
                    <li class="list-group-item">{{ $loop->iteration }}. {{ $region->name }}</li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('federals.index') }}" class="btn btn-secondary">Back to Federals</a>
    </div>
</div>
@endsection
