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
    <h1 class="mb-4">Edit Shop</h1>
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
        <i class="ri-arrow-go-back-fill"></i> Back to shops
    </a>

    <form action="{{ route('shops.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Shop Name -->
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Shop Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $shop->name) }}" required>
                </div>

                <!-- Owners Select Field -->
                <div class="mb-3">
                    <label for="owner_id" class="form-label">Owners</label>
                    <select class="form-select" id="owner_id" name="owner_id" required>
                        <option value="">Select Owners</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ $shop->owner_id == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Phone Number Input Field -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $shop->phone) }}" required>
                </div>

                <!-- Total Capital Input Field -->
                <div class="mb-3">
                    <label for="total_capital" class="form-label">Total Capital</label>
                    <input type="number" class="form-control" id="total_capital" name="total_capital" step="0.01" value="{{ old('total_capital', $shop->total_capital) }}" required>
                </div>

                <!-- Opening Hours Input Field -->
                <div class="mb-3">
                    <label for="opening_hours" class="form-label">Opening Hours</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $shop->opening_hours) }}" required>
                </div>

                <!-- Category Select Field -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $shop->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Select Field -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="active" {{ $shop->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $shop->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- FederalAdmin Field -->
                @if(Auth::user()->hasRole('FederalAdmin'))
                    <div class="mb-3">
                        <label for="federal_id" class="form-label">Federal</label>
                        <select class="form-select" id="federal_id" name="federal_id" required>
                            <option value="">Select Federal</option>
                            @foreach($federals as $federalOption)
                                <option value="{{ $federalOption->id }}" {{ $shop->federal_id == $federalOption->id ? 'selected' : '' }}>
                                    {{ $federalOption->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- RegionalAdmin Field -->
                @if(Auth::user()->hasRole('RegionalAdmin'))
                    <div class="mb-3">
                        <label for="region_id" class="form-label">Region</label>
                        <select class="form-select" id="region_id" name="region_id" required>
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}" {{ $shop->region_id == $region->id ? 'selected' : '' }}>
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- ZoneAdmin Field -->
                @if(Auth::user()->hasRole('ZoneAdmin'))
                    <div class="mb-3">
                        <label for="zone_id" class="form-label">Zone</label>
                        <select class="form-select" id="zone_id" name="zone_id" required>
                            <option value="">Select Zone</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ $shop->zone_id == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- WoredaAdmin Field -->
                @if(Auth::user()->hasRole('WoredaAdmin'))
                    <div class="mb-3">
                        <label for="woreda_id" class="form-label">Woreda</label>
                        <select class="form-select" id="woreda_id" name="woreda_id" required>
                            <option value="">Select Woreda</option>
                            @foreach($woredas as $woreda)
                                <option value="{{ $woreda->id }}" {{ $shop->woreda_id == $woreda->id ? 'selected' : '' }}>
                                    {{ $woreda->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- KebeleAdmin Field -->
                @if(Auth::user()->hasRole('KebeleAdmin'))
                    <div class="mb-3">
                        <label for="kebele_id" class="form-label">Kebele</label>
                        <select class="form-select" id="kebele_id" name="kebele_id" required>
                            <option value="">Select Kebele</option>
                            @foreach($kebeles as $kebele)
                                <option value="{{ $kebele->id }}" {{ $shop->kebele_id == $kebele->id ? 'selected' : '' }}>
                                    {{ $kebele->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Shop License Upload -->
                <div class="mb-3">
                    <label for="shop_license" class="form-label">Shop License (PDF)</label>
                    <input type="file" class="form-control" id="shop_license" name="shop_license" accept="application/pdf">
                    @if($shop->shop_license_path)
                        <p>Current License: <a href="{{ asset('storage/' . $shop->shop_license_path) }}" target="_blank">View License</a></p>
                    @endif
                </div>

              <!-- TIN Input Field -->
                <div class="mb-3">
                    <label for="TIN" class="form-label">Tax Identification Number (TIN)</label>
                    <input type="text" class="form-control" id="TIN"  name="TIN"  maxlength =12 required>
                </div>
                <!-- Website Input Field -->
                <div class="mb-3">
                    <label for="website" class="form-label">Website</label>
                    <input type="url" class="form-control" id="website" name="website" value="{{ old('website', $shop->website) }}">
                </div>
            </div>

            <!-- Google Maps for Latitude and Longitude -->
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <div id="map"></div>
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $shop->latitude) }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $shop->longitude) }}">
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <button type="submit" class="btn btn-primary">Update Shop</button>
    </form>
</div>

<!-- Initialize Google Map -->
<script>
    function initMap() {
        const latitude = {{ $shop->latitude ?? 9.145 }};
        const longitude = {{ $shop->longitude ?? 40.489673 }};

        const mapOptions = {
            zoom: 8,
            center: { lat: latitude, lng: longitude }
        };

        const map = new google.maps.Map(document.getElementById("map"), mapOptions);

        // Marker for existing shop location
        const marker = new google.maps.Marker({
            position: { lat: latitude, lng: longitude },
            map: map,
            draggable: true // Allow marker dragging
        });

        // Update latitude and longitude fields when marker is dragged
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });

        // Event listener for map click
        google.maps.event.addListener(map, 'click', function(event) {
            const lat = event.latLng.lat(); // Get latitude from click
            const lng = event.latLng.lng(); // Get longitude from click

            // Set the values of latitude and longitude fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Move the marker to the clicked location
            marker.setPosition({ lat: lat, lng: lng });
        });
    }
</script>
@endsection
