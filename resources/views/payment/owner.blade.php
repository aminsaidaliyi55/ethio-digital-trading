@extends('layouts.app')

@section('content')


<div class="container">
    <div class="alert alert-info">
        <strong>@lang('messages.payment_instructions_title')</strong><br>
        @lang('messages.payment_instruction_text')
        <ul class="mt-2">
            <li><strong>@lang('messages.bank'):</strong> @lang('messages.bank_name')</li>
            <li><strong>@lang('messages.account_number'):</strong> 1000508955905</li>
            <li><strong>@lang('messages.account_name'):</strong> Amin Said Aliyi</li>
        </ul>
        @lang('messages.after_payment_instruction')
    </div>

    <form method="POST" action="{{ route('owner.payment.process') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="screenshot">@lang('messages.upload_screenshot_label')</label>
            <input type="file" class="form-control" name="screenshot" required accept=".jpg,.jpeg,.png,.pdf">
            @error('screenshot')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">@lang('messages.submit_for_approval')</button>
    </form>
</div>
@endsection
