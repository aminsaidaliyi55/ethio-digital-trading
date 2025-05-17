@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shop Details</h1>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back to Shop List</a>
    
    <div class="card">
        <div class="card-header">
            <h5>{{ $shop->name }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Owner Name:</h6>
                    <p>{{ $shop->owner ? $shop->owner->name : 'N/A' }}</p>

                    <h6>Phone:</h6>
                    <p>{{ $shop->phone ? $shop->phone : 'N/A' }}</p>

                    <h6>Status:</h6>
                    <p>{{ $shop->status }}</p>

                    <h6>Shop License:</h6>
                    <p>{{ $shop->shop_license ? $shop->shop_license : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Shop Location (Traffic Route)</h5>
        </div>
        <div class="card-body">
            <div id="google-map" style="height: 400px;"></div>
            <div id="distance-info">
                <h6>Distance from Your Location: <span id="distance"></span></h6>
                <h6>Estimated Travel Time (with Traffic): <span id="duration"></span></h6>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=geometry,directions&callback=initGoogleMap" async defer></script>

<script>
    function initGoogleMap() {
        const latitude = parseFloat({{ $shop->latitude ?? '0' }});
        const longitude = parseFloat({{ $shop->longitude ?? '0' }});

        if (!latitude || !longitude) {
            console.error("Invalid coordinates for Google Map");
            return;
        }

        const shopLocation = { lat: latitude, lng: longitude };

        const map = new google.maps.Map(document.getElementById("google-map"), {
            zoom: 15,
            center: shopLocation,
        });

        const markerShop = new google.maps.Marker({
            position: shopLocation,
            map: map,
            title: "{{ $shop->name }}",
        });

        // Get current location and route with traffic
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                const markerUser = new google.maps.Marker({
                    position: userLocation,
                    map: map,
                    title: "Your Location",
                });

                // Directions Service
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    polylineOptions: {
                        strokeColor: '#00FF00', // Green color
                        strokeWeight: 4,
                    }
                });

                directionsRenderer.setMap(map);

                const request = {
                    origin: userLocation,
                    destination: shopLocation,
                    travelMode: google.maps.TravelMode.DRIVING,
                    provideRouteAlternatives: false,
                    drivingOptions: {
                        departureTime: new Date(Date.now()), // for the time now
                        trafficModel: 'bestguess'
                    }
                };

                directionsService.route(request, (response, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(response);
                        const distance = response.routes[0].legs[0].distance.text;
                        const duration = response.routes[0].legs[0].duration_in_traffic.text;
                        document.getElementById("distance").innerText = distance; // Display distance
                        document.getElementById("duration").innerText = duration; // Display estimated travel time
                    } else {
                        console.error('Error fetching directions: ' + status);
                    }
                });
            });
        }
    }
</script>
@endsection
