@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">Create New Kebele</div>
    <div class="card-body">
        <form action="{{ route('kebeles.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Kebele Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="woreda_id" class="form-label">Select Woreda</label>
                <select name="woreda_id" id="woreda_id" class="form-select @error('woreda_id') is-invalid @enderror" required>
                    <option value="">-- Select Woreda --</option>
                    @foreach($woredas as $woreda)
                        <option value="{{ $woreda->id }}">{{ $woreda->name }}</option>
                    @endforeach
                </select>
                @error('woreda_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Create Kebele</button>
            <a href="{{ route('kebeles.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
