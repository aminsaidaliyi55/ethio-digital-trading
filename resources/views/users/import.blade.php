@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Import Users</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('users.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label">Choose Excel File</label>
            <input type="file" name="file" class="form-control" id="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Import Users</button>
    </form>
</div>
@endsection
