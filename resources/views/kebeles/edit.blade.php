@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Edit Kebele</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('kebeles.update', $kebele->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Kebele Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $kebele->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="woreda_id" class="form-label">Woreda</label>
                <select id="woreda_id" name="woreda_id" class="form-select @error('woreda_id') is-invalid @enderror" required>
                    <option value="">Select Woreda</option>
                    @foreach ($woredas as $woreda)
                        <option value="{{ $woreda->id }}" {{ $woreda->id == $kebele->woreda_id ? 'selected' : '' }}>{{ $woreda->name }}</option>
                    @endforeach
                </select>
                @error('woreda_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Kebele</button>
            <a href="{{ route('kebeles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
