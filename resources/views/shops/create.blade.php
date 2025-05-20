@extends('layouts.app')

@section('content')
<style>
    /* Scrollable page CSS */
    html, body {
        height: 100%;
        margin: 0;
        overflow: auto;
    }

    .scrollable-container {
        max-height: 80vh;
        overflow-y: auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    #map {
        height: 300px;
        margin-bottom: 20px;
    }
</style>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAaYuHMfzLtlyKYIEDXj7JcbQp0m-qOSvE&callback=initMap" async defer></script>

<div class="container scrollable-container">
    <h1 class="mb-4">Create Shop</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('shops.index') }}" class="btn btn-secondary mb-3">
        <i class="ri-arrow-go-back-fill"></i> Back to shop
    </a>

    <form action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Shop Name -->
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Shop Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

               <!-- Owners Select Field -->
<div class="mb-3">
    <label for="owner_id" class="form-label">Owners</label>
    <select class="form-select" id="owner_id" name="owner_id" required>
        <option value="">Select Owner</option>

        @if(Auth::user()->hasRole('Super Admin'))
            {{-- Super Admin can see all owners --}}
            @foreach($owners as $owner)
                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
            @endforeach
        @else
            {{-- Other users (e.g., just "Owners" role) only see themselves --}}
            <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
        @endif

    </select>
</div>


                <!-- Phone Number Input Field -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>

                <!-- Total Capital Input Field -->
                <div class="mb-3">
                    <label for="total_capital" class="form-label">Total Capital</label>
                    <input type="number" class="form-control" id="total_capital" name="total_capital" step="0.01" placeholder="Enter total capital" required>
                </div>

                <!-- Opening Hours Input Field -->
                <div class="mb-3">
                    <label for="opening_hours" class="form-label">Opening Hours</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" placeholder="e.g., 9:00 AM - 5:00 PM" required>
                </div>

                <!-- Category Select Field -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Select Field -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label hidden>
                    <select class="form-select" id="status" name="status" required>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Other Fields based on Role -->

                <!-- FederalAdmin Field -->
                @if(Auth::user()->hasRole('FederalAdmin'))
                <div class="mb-3">
                    <label for="federal_id" class="form-label">Federal</label>
                    <select class="form-select" id="federal_id" name="federal_id" required>
                        <option value="">Select Federal</option>
                        @foreach($federals as $federalOption)
                            <option value="{{ $federalOption->id }}">{{ $federalOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- RegionalAdmin Field -->
                @if(Auth::user()->hasRole('RegionalAdmin'))
                    <div class="mb-3">
                        <label for="region_id" class="form-label">Region</label>
                        <select class="form-select" id="region_id" name="region_id" hidden>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- ZoneAdmin Field -->
                @if(Auth::user()->hasRole('ZoneAdmin'))
                    <div class="mb-3">
                        <label for="zone_id" class="form-label">Zone</label>
                        <select class="form-select" id="zone_id" name="zone_id" hidden>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- WoredaAdmin Field -->
                @if(Auth::user()->hasRole('WoredaAdmin'))
                    <div class="mb-3">
                        <select class="form-select" id="woreda_id" name="woreda_id" hidden>
                            @foreach($woredas as $woreda)
                                <option value="{{ $woreda->id }}">{{ $woreda->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- KebeleAdmin Field -->
                @if(Auth::user()->hasRole('KebeleAdmin'))
                    <div class="mb-3">
                        <select class="form-select" id="kebele_id" name="kebele_id" hidden>
                            @foreach($kebeles as $kebele)
                                <option value="{{ $kebele->id }}">{{ $kebele->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Shop License Upload -->
                <div class="mb-3">
                    <label for="shop_license" class="form-label">Shop License (PDF)</label>
                    <input type="file" class="form-control" id="shop_license" name="shop_license" accept="application/pdf" required>
                </div>

                <!-- TIN Input Field -->
                <div class="mb-3">
                    <label for="TIN" class="form-label">Tax Identification Number (TIN)</label>
                    <input type="text" class="form-control" id="TIN"  name="TIN"  maxlength =12 required>
                </div>

                <!-- Website Input Field -->
                <div class="mb-3">
                    <label for="website" class="form-label">Website</label>
                    <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com" required>
                </div>

                <!-- Coordinates (Latitude and Longitude) -->
                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" required>
                </div>

                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" required>
                </div>

                <!-- Map -->
                <div id="map"></div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Create Shop</button>
            </div>
        </div>
    </form>
</div>

<script>
    // Initialize Google Map
    function initMap() {
        const mapOptions = {
            center: { lat: 0, lng: 0 }, // Default center (You can change this to a specific location if needed)
            zoom: 2, // Zoom level (can be adjusted)
        };

        const map = new google.maps.Map(document.getElementById("map"), mapOptions);

        // Event listener for map click
        google.maps.event.addListener(map, 'click', function(event) {
            const lat = event.latLng.lat(); // Get latitude from click
            const lng = event.latLng.lng(); // Get longitude from click

            // Set the values of latitude and longitude fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    }
</script>

@endsection
