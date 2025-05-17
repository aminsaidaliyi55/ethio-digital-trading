@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assign Federal Admin</h1>
    <form action="{{ route('federals.assignAdmin') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="federal_id">Federal</label>
            <select name="federal_id" id="federal_id" class="form-control" required>
                @foreach($federals as $federal)
                    <option value="{{ $federal->id }}">{{ $federal->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="admin_id">Admin</label>
            <select name="admin_id" id="admin_id" class="form-control" required>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assign Admin</button>
    </form>
</div>
@endsection
