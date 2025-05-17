@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">@lang('messages.Dashboard')</div>
                @if(session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
@endif
<!-- resources/views/layouts/app.blade.php or any other parent layout -->
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Your other content -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

 
               
            </div>
        </div>
    </div>
</div>
@endsection