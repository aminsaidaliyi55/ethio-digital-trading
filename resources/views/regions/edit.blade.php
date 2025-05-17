@extends('layouts.app') <!-- Extending the base layout -->

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Region</h1>

    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to edit the existing region -->
    <form action="{{ route('regions.update', $region->id) }}" method="POST">
        @csrf <!-- CSRF token for form security -->
        @method('PUT') <!-- Method spoofing for PUT -->

        <div class="mb-3">
            <label for="name" class="form-label">Region Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $region->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="federal_id" class="form-label">Select Federal</label>
            <select name="federal_id" id="federal_id" class="form-select" required>
                <option value="">Select Federal</option>
                @foreach($federals as $federal)
                    <option value="{{ $federal->id }}" {{ $region->federal_id == $federal->id ? 'selected' : '' }}>
                        {{ $federal->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Region</button>
        <a href="{{ route('regions.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
