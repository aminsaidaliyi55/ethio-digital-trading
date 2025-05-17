@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Woreda</h1>

    <!-- Back Button -->
    <a href="{{ route('home') }}" class="btn btn-secondary mb-3">Back </a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to create woreda -->
    <form action="{{ route('woredas.store') }}" method="POST">
        @csrf

        <!-- Select Zone -->
        <div class="form-group mb-3">
            <label for="zone_id">Select Zone:</label>
            <select name="zone_id" id="zone_id" class="form-control">
                <option value="" disabled selected>Select Zone</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Woreda Name -->
        <div class="form-group mb-3">
            <label for="name">Woreda Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Woreda</button>
    </form>
</div>
@endsection
