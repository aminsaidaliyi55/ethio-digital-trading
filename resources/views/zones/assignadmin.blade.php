@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assign Admin to Zone: {{ $zone->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('zones.store-admin', $zone->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="admin_id" class="form-label">Select Admin</label>
            <select name="admin_id" id="admin_id" class="form-select" required>
                <option value="">-- Select Admin --</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-success">Assign Admin</button>
        <a href="{{ route('zones.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
