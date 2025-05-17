@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Zone</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('zones.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Zone Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label for="region_id" class="form-label">Select Region</label>
        <select name="region_id" id="region_id" class="form-control" required>
            <option value="">-- Select region --</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Zone</button>
    <a href="{{ route('zones.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
