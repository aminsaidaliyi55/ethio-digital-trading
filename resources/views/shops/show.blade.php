@extends('layouts.app')

@section('content')
<style>
    /* Add CSS for scrollable page */
    html, body {
        height: 100%; /* Ensure full height */
        margin: 0; /* Remove default margins */
        overflow: auto; /* Ensure scrolling is enabled */
    }

    .scrollable-container {
        max-height: calc(100vh - 500px); /* Adjust height to leave room for the map */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 20px; /* Add padding for aesthetics */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
    }

    #map {
        height: calc(100vh - 200px); /* Set map height to fill remaining window space */
        margin-top: 20px; /* Space above the map */
        width: 100%; /* Ensure the map takes full width */
    }
</style>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Include Leaflet Control Geocoder for search functionality -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<!-- Include Leaflet Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<div class="scrollable-container">
    <h4>{{ $shop->name }}</h4>
    <a href="{{ route('shops.index') }}" class="btn btn-secondary mb-3">
        <i class="ri-arrow-go-back-fill"></i> Back to shop
    </a>
    <ul class="list-group">
        <li class="list-group-item"><strong>Owner Name:</strong> {{ $shop->owner ? $shop->owner->name : 'N/A' }}</li>
    </ul>
</div>

<h4>Shop Location</h4>
<div id="map"></div>

<script>
    var map = L.map('map').setView([{{ $shop->latitude }}, {{ $shop->longitude }}], 12);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add a marker for the shop location
    var shopMarker = L.marker([{{ $shop->latitude }}, {{ $shop->longitude }}]).addTo(map)
        .bindPopup("Shop of {{ $shop->owner ? $shop->owner->name : 'Unknown Owner' }}")
        .openPopup();

    // User location tracking
    map.locate({setView: true, maxZoom: 16});
    var userMarker;

    map.on('locationfound', function(e) {
        userMarker = L.marker(e.latlng).addTo(map)
            .bindPopup("You are here!")
            .openPopup();
        
        // Start the slideshow effect
        slideToShop(e.latlng, [{{ $shop->latitude }}, {{ $shop->longitude }}]);
    });

    map.on('locationerror', function(e) {
        alert(e.message);
    });

    function slideToShop(userLocation, shopLocation) {
        // Create a route between the user's location and the shop's location
        var waypoints = [
            L.latLng(userLocation.lat, userLocation.lng),
            L.latLng(shopLocation[0], shopLocation[1])
        ];

        // Use the Leaflet Routing Machine to calculate the route
        L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: true,
            createMarker: function() { return null; } // Disable markers on route
        }).addTo(map);

        // Get the distance in kilometers
        var distance = userLocation.distanceTo(shopLocation) / 1000;

        // Slideshow effect
        let zoom = 15; // Zoom level for the transition
        let duration = 2000; // Duration of the transition in milliseconds
        let steps = 100; // Number of steps in the transition
        let stepDuration = duration / steps;
        
        let latStep = (shopLocation[0] - userLocation.lat) / steps;
        let lngStep = (shopLocation[1] - userLocation.lng) / steps;

        // Animation loop
        let currentLat = userLocation.lat;
        let currentLng = userLocation.lng;

        let i = 0;
        let interval = setInterval(function() {
            if (i >= steps) {
                clearInterval(interval);
                return;
            }
            currentLat += latStep;
            currentLng += lngStep;
            map.setView([currentLat, currentLng], zoom);
            i++;
        }, stepDuration);
    }
</script>
@endsection
