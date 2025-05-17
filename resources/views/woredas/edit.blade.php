@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">Edit Woreda</div>
    <div class="card-body">
        <form action="{{ route('woredas.update', $woreda->id) }}" method="POST">
            @csrf
            @method('PUT')
 <div class="mb-3">
                <label for="zone_id" class="form-label">Zone</label>
                <select class="form-select @error('zone_id') is-invalid @enderror" id="zone_id" name="zone_id" required>
                    <option value="" disabled>Select Zone</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}" {{ old('zone_id', $woreda->zone_id) == $zone->id ? 'selected' : '' }}>
                            {{ $zone->name }}
                        </option>
                    @endforeach
                </select>
                @error('zone_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Woreda Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $woreda->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

           

          

            <button type="submit" class="btn btn-primary">Update Woreda</button>
            <a href="{{ route('woredas.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
