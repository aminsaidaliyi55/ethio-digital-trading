@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer List</h1>

    <!-- Flash message for success/failure -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('Customer.create') }}" class="btn btn-primary mb-3">Add New Customer</a>

    <!-- Search and Filter Form -->
    <form method="GET" class="form-inline mb-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="form-control mr-2">
        <!-- Cities and states dropdowns if you have predefined options -->
        <select name="city" class="form-control mr-2">
            <option value="">All Cities</option>
            <!-- Assume you have City1 and City2 as options -->
            <option value="City1" {{ request('city') == 'City1' ? 'selected' : '' }}>City1</option>
            <option value="City2" {{ request('city') == 'City2' ? 'selected' : '' }}>City2</option>
        </select>
        <select name="state" class="form-control mr-2">
            <option value="">All States</
