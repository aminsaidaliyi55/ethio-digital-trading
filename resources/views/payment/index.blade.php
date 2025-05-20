@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">@lang('messages.Payment') Management</h4>

    <div class="mb-3">
        <form method="GET" action="{{ route('payment.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Filter by Status --</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('payment.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Payment Screenshot</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>
                        @if($user->payment_screenshot)
                            @php
                                $filePath = 'storage/' . $user->payment_screenshot;
                                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                            @endphp

                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
<img src="{{ asset($filePath) }}"
     alt="{{ $user->payment_screenshot }}"
     class="img-thumbnail zoom-trigger" style="width: 60px; height: auto; cursor: pointer;"
     data-bs-toggle="modal" data-bs-target="#imageModal{{ $user->id }}">

                            @elseif(strtolower($ext) === 'pdf')
                                <a href="{{ asset($filePath) }}" target="_blank">View PDF</a>
                            @else
                                <a href="{{ asset($filePath) }}" target="_blank">{{ basename($filePath) }}</a>
                            @endif
                        @else
                            <span class="text-danger">@lang('messages.No Image')</span>
                        @endif
                    </td>
                    <td>
                        @if(!$user->is_approved)
                            <span class="text-warning">Pending</span>
                        @else
                            <span class="text-success">Approved</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            @if($user->payment_screenshot)
                                <a href="{{ asset('storage/'.$user->payment_screenshot) }}" class="btn btn-sm btn-info" target="_blank">View</a>
                                <a href="{{ asset('storage/'.$user->payment_screenshot) }}" class="btn btn-sm btn-secondary" download>Download</a>
                            @endif

                            @if(!$user->is_approved)
                                <form method="POST" action="{{ route('payment.approve', $user->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                                </form>
                            @else
                                <span class="text-muted">No Action</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $users->withQueryString()->links() }}
    </div>
</div>

<!-- Image Modals -->
@foreach($users as $user)
    @if($user->payment_screenshot && in_array(strtolower(pathinfo($user->payment_screenshot, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']))
        <div class="modal fade" id="imageModal{{ $user->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel{{ $user->id }}">{{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('messages.Close')"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $user->payment_screenshot) }}" alt="{{ $user->name }}" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

@push('styles')
<style>
 .payment-thumbnail {
    width: 60px;
    height: auto;
        transition: transform 0.2s ease;
        cursor: zoom-in;
}


    .payment-thumbnail:hover {
    transform: scale(2.5);
        z-index: 9999;
        position: relative;    }

         .payment-thumbnail {
    width: 60px;
    height: auto;
        transition: transform 0.2s ease;
        cursor: zoom-in;
}

   .payment-thumbnail:hover {
    transform: scale(2.5);
        z-index: 9999;
        position: relative;    }


    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Optional: style scrollbars in WebKit browsers */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

@endpush

@push('scripts')
<!-- Include Bootstrap JS if not already included -->
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
