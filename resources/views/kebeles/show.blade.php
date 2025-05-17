@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Kebele Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Kebele Name: {{ $kebele->name }}</h5>

            <p class="card-text">
                <strong>Woreda:</strong> 
                {{ $kebele->woreda ? $kebele->woreda->name : 'No Woreda Assigned' }}
            </p>

            <p class="card-text">
                <strong>Assigned Admin:</strong> 
                {{ $kebele->admin ? $kebele->admin->name : 'No Admin Assigned' }}
            </p>
              <a href="{{ route('home') }}" class="btn btn-secondary">
                   Back
                </a>

            <!-- Add other fields as needed -->
                @if (Auth::user()->region_id === $kebele->woreda->zone->region_id) <!-- Check if user's region_id matches kebele's region_id -->

            @can('kebeles-edit')

                <a href="{{ route('kebeles.edit', $kebele->id) }}" class="btn btn-primary">
                    Edit Kebele
                </a>
           
                    <form action="{{ route('kebeles.destroy', $kebele->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Kebele?')">Delete</button>
                    </form>
                @endif
            @endcan
        </div>
    </div>
</div>
@endsection
