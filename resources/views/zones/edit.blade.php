@extends('layouts.app') <!-- Extending the base layout -->

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Zone</h1>

    @if(session('success')) <!-- Display success message -->
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <form action="{{ route('zones.update', $zone->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT method for update -->

        <!-- Zone Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Zone Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $zone->name) }}" required>
        </div>

        <!-- Federal Selection -->
        <div class="mb-3">
            <label for="federal_id" class="form-label">Regions</label>
            <select name="region_id" id="region_id" class="form-select" required>
                <option value="">Select a region</option>
                @foreach($regions as $regions)
                    <option value="{{ $regions->id }}" {{ old('region_id', $zone->region_id) == $regions->id ? 'selected' : '' }}>
                        {{ $regions->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Zone</button>

        <!-- Back Button -->
        <a href="{{ route('zones.index') }}" class="btn btn-secondary">Back to List</a>
    </form>
</div>
@endsection
