<!doctype html> 
<html lang="en">
 
  <head> 
 
    <meta charset="utf-8" /> 
    <title>Ethiopian Online Trading and  Market Control System</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" /> 
    <meta content="Themesdesign" name="author" /> 
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

     <link id="bootstrap-style" href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> 
    <link id="app-style" href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" /> 
    <!-- Add IDs to other stylesheets as needed --> 
    <!-- App favicon --> 
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}"> 
<!-- DataTables CSS --> 
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> 
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" /> 
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script> 
 
 
<!-- DataTables JS --> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 
 
    <!-- jquery.vectormap css --> 
    <link href="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" /> 
 
    <!-- DataTables --> 
    <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> 
 
    <!-- Responsive datatable examples --> 
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />   
 
    <!-- Bootstrap Css --> 
    <link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> 
     
    <!-- Icons Css --> 
    <link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" /> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ethiopian-date-picker/dist/css/ethiopianDatePicker.css"> 
   <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" /> 
 <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Leaflet JavaScript --> 
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/ethiopian-date-picker/dist/js/ethiopianDatePicker.js"></script> 
<!-- Include jQuery (if not already included) --> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
 
<!-- Include Bootstrap JS --> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> 
 
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> 
<script> 
    $(document).ready(function () { 
        // Initialize the Ethiopian Date Picker 
        $('#expiration_date').ethiopianDatePicker({ 
            // Customize the date picker if needed 
            startYear: 2000,    // Start year in Ethiopian calendar 
            endYear: 2050,      // End year in Ethiopian calendar 
            closeOnSelect: true // Close the picker after selecting a date 
        }); 
    }); 
</script> 
 
<script> 
    var map = L.map('map').setView([latitude, longitude], zoomLevel); // Replace with your coordinates 
 
    // Add satellite tile layer 
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
        attribution: '© OpenStreetMap contributors' 
    }).addTo(map); 
     
    // If you want to use satellite tiles, you can use Mapbox or similar providers 
    L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-v9/tiles/{z}/{x}/{y}?access_token=YOUR_MAPBOX_ACCESS_TOKEN', { 
        maxZoom: 20, 
        attribution: 'Map data © OpenStreetMap contributors, CC-BY-SA, Imagery © Mapbox', 
        tileSize: 512, 
        zoomOffset: -1 
    }).addTo(map); 
</script> 
<script> 
    function initMap() { 
        var mapOptions = { 
            center: { lat: latitude, lng: longitude }, // Replace with your coordinates 
            zoom: zoomLevel, // Replace with your desired zoom level 
            mapTypeId: 'satellite' // Set map type to satellite 
        }; 
 
        var map = new google.maps.Map(document.getElementById('map'), mapOptions); 
    } 
</script> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script> 
 
    <!-- App Css--> 
    <link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" /> 
 
    <link id="bootstrap-style" href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> 
    <link id="app-style" href="{{ asset('backend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" /> 
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" /> 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> 
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.ico') }}"> 
 
    <!-- Scripts --> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> 
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script> 
 
    
</head> 
 
    <body data-topbar="dark"> 
      <!--- Sidemenu --> 
                    <div id="sidebar-menu" data-translate="true"> 
                        <!-- Left Menu Start --> 
                        <ul class="metismenu list-unstyled" id="side-menu"> 
                            @guest 
                            @if (Route::has('login')) 
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a> 
                                </li> 
                            @endif 
 @if (Route::has('register')) 
                                <li class="nav-item"> 
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a> 
                                </li> 
                            @endif 
                        @else 
 
    <!-- <body data-layout="horizontal" data-topbar="dark"> --> 
 
        <!-- Begin page --> 
        <div id="layout-wrapper">
        <header id="page-topbar">
  <div class="navbar-header">
    <div class="d-flex">
      <!-- LOGO -->
      <div class="navbar-brand-box">
        <a href="{{route('home')}}" class="logo logo-light">
          <span class="logo-lg">
            <img src="{{ asset('storage/profile_images/' .'Amin Supper Admin_2.png') }}" alt="logo-light" height="71" width="300">
          </span>
        </a>
      </div>

      <button type="button" class="btn btn-sm px-3 font-size-34 header-item waves-effect" id="vertical-menu-btn">
        <i class="ri-menu-2-line align-middle"></i>
      </button>
    </div>

  <ul class="navbar-nav mx-auto">
    @if(Auth::user()->hasRole('Owners'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="ownerNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1.2rem;">
                <i class="ri-notification-3-line" style="font-size: 1.5rem;"></i>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger" style="font-size: 0.75rem; padding: 0.35em 0.6em;">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="ownerNotifications">
                @forelse(Auth::user()->unreadNotifications as $notification)
                    <li>
                        <a class="dropdown-item" href="{{ route('orders.index', $notification->data['order_id']) }}">
                            📦 {{ $notification->data['message'] }}
                        </a>
                    </li>
                @empty
                    <li><span class="dropdown-item">No new notifications</span></li>
                @endforelse
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('notifications.markAsRead') }}">
                        @csrf
                        <button class="dropdown-item text-center" type="submit">Mark all as read</button>
                    </form>
                </li>
            </ul>
        </li>
    @endif
</ul>





<!-- Language Dropdown -->
<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        🌐 @lang('messages.Language')
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'en']) }}">🇬🇧 English</a></li>
        <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'am']) }}">🇪🇹 አማርኛ (Amharic)</a></li>
        <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'so']) }}">🇸🇴 Soomaali</a></li>
        <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'om']) }}">🇪🇹 Oromoo</a></li>
        <li><a class="dropdown-item" href="{{ route('lang.switch', ['locale' => 'ti']) }}">🇪🇷 ትግርኛ (Tigrinya)</a></li>
    </ul>
</div>


    <!-- User Dropdown -->
    <div class="dropdown d-inline-block user-dropdown" data-translate="true">
        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ asset('storage/profile_images/' . Auth::user()->image) }}" alt="Profile Avatar" class="avatar-md rounded-circle">
            <span class="d-none d-xl-inline-block ms-1" id="username">{{ Auth::user()->name }}</span>
            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
        </button>

        <!-- Dropdown Menu (Initially Hidden) -->
        <div class="dropdown-menu dropdown-menu-end" style="min-width: 200px; padding: 10px 0; display: none;" id="user-dropdown-menu">
            <!-- Profile Option -->
            <a class="dropdown-item" href="{{ route('profile.show') }}" style="font-size: 14px; padding: 8px 16px;">
                <i class="ri-user-line align-middle me-2" style="color: #007bff;"></i> Profile
            </a>

            <div class="dropdown-divider"></div>

            <!-- Logout Option -->
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #dc3545; font-size: 14px; padding: 8px 16px;">
                <i class="ri-logout-box-line align-middle me-2" style="color: #dc3545;"></i> Logout
            </a>

            <!-- Hidden Logout Form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
</header>

<script>
    const usernameElement = document.getElementById('username');
    const dropdownMenu = document.getElementById('user-dropdown-menu');
    usernameElement.addEventListener('click', function() {
        dropdownMenu.style.display = (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') ? 'block' : 'none';
    });
</script>

<div class="translatedText">
    <div class="vertical-menu" data-translate="true">
        <div data-simplebar class="h-100">
            <div class="user-profile text-center mt-3">
                <div class=""></div>
                <div class="mt-3">
                    <a href="{{route('home')}}" class="waves-effect">
                        <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end"></span>
                        <span>
                            <div class="d-flex align-items-center ms-3">
                                @auth
                                @if (Auth::user()->hasRole('FederalAdmin')) 
                                 <span class="badge bg-primary">
    {{ Auth::user()->federal ? Auth::user()->federal->name : __('No Federal Assigned') }} {{ __('Federals') }}
</span>

                                @elseif (Auth::user()->hasRole('RegionalAdmin')) 
                                 <span class="badge bg-success"> 
    {{ Auth::user()->region ? Auth::user()->region->name : __('No Region Assigned') }} {{ __('Region') }} 
</span>

                                @elseif (Auth::user()->hasRole('Super Admin')) 
                                  <span class="badge bg-success"> 
    {{ Auth::user()->region ? Auth::user()->region->name : __('SuperAdmin') }}
</span>

                                @elseif (Auth::user()->hasRole('ZoneAdmin')) 
                                 <span class="badge bg-success"> 
    {{ Auth::user()->zone ? Auth::user()->zone->name : __('No Zone Assigned') }} {{ __('Zone') }} 
</span>

                                @elseif (Auth::user()->hasRole('WoredaAdmin')) 
                              <span class="badge bg-success"> 
    {{ Auth::user()->woreda ? Auth::user()->woreda->name : __('No Woreda Assigned') }} {{ __('Woreda') }} 
</span>

                                @elseif (Auth::user()->hasRole('KebeleAdmin')) 
                                    <span class="badge bg-success"> 
    {{ Auth::user()->kebele ? Auth::user()->kebele->name : __('No Kebele Assigned') }} {{ __('Kebele') }} 
</span>
 
                                @elseif (Auth::user()->hasRole('Owners')) 
                                    <span class="badge bg-success"> 
                                        @lang('messages.Owner Of this Shop') 
                                    </span> 
                                @elseif (Auth::user()->hasRole('Customer')) 
                                    <span class="badge bg-success"> 
                                        @lang('messages.Customer')
                                    </span> 
                                @else 
                                    <span class="badge bg-success">@lang('messages.Has No Roles')</span> 
                                @endif 
                                @endauth 
                            </div>
                        </span>
                    </a>
                </div>
            </div>

            @canany(['create-role', 'edit-role', 'delete-role']) 
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect"> 
                    <i class="ri-user-fill"></i> 
                    <span data-translate="true"> @lang('messages.Roles And Permission')</span> 
                </a> 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('permissions.index') }}">@lang('messages.Permission')</a></li> 
                </ul> 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('roles.index') }}">@lang('messages.Role')</a></li> 
                </ul> 
            </li> 
            @endcanany

            @canany(['create-user', 'edit-user', 'delete-user']) 
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect"> 
                    <i class="ri-user-fill"></i> 
                    <span> @lang('messages.Users')</span> 
                </a> 
                <ul class="sub-menu" aria-expanded="false"> 
                    <a href="{{ route('users.index') }}">@lang('messages.Users')</a></li> 
                </ul> 
            </li> 
            @endcanany  

            @if (!Auth::user()->hasAnyRole(['Owners', 'Customer']))
            <li> 
                <a href="javascript: void(0);" class="has-arrow waves-effect"> 
                    <i class="ri-government-line"></i> 
                    <span>@lang('messages.Governmental Structure')</span> 
                </a> 

                @can(['regions-view', 'regions-create']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('federals.index') }}">@lang('messages.At the Federal')</a></li> 
                </ul> 
                @endcan 

                @can(['regions-view', 'regions-create']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('regions.index') }}">@lang('messages.Regions')</a></li> 
                </ul> 
                @endcan 

                @can(['zones-view', 'zones-create']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('zones.index') }}">@lang('messages.Zones')</a></li> 
                </ul> 
                @endcan 

                @can(['woredas-view', 'woredas-create']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('woredas.index') }}">@lang('messages.Woredas')</a></li> 
                </ul> 
                @endcan 

                @can(['kebeles-create']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('kebeles.index') }}">@lang('messages.Kebeles')</a></li> 
                </ul> 
                @endcan 
            </li> 
            @endif 

            <li> 
                <a href="javascript: void(0);" class="has-arrow waves-effect"> 
                    <i class="ri-home-2-line"></i> 
                    <span>@lang('messages.Shops')</span> 
                </a> 
                @can(['show-shop']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('shops.index') }}">@lang('messages.Manage Shops')</a></li> 
                </ul> 
                @endcan

                @can(['create-category','edit-category', 'delete-category']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('category.index') }}">@lang('messages.Manage Job Categories')</a></li> 
                </ul> 
                @endcan 

                @canany(['create-product','view-product', 'edit-product', 'delete-product']) 
                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('products.index') }}">@lang('messages.Products')</a></li> 
                </ul> 
                @endcanany 

                <ul class="sub-menu" aria-expanded="false"> 
                    <li><a href="{{ route('orders.index') }}">@lang('messages.Orders')</a></li> 
                </ul> 
            </li>

            <li>
                @canany(['show-report', 'print-report', 'generate-report'])
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="ri-home-2-line"></i>
                    <span>@lang('messages.Reports')</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ route('dailyreport.index') }}">@lang('messages.Daily Reports')</a></li>
                    <li><a href="{{ route('monthlyreport.index') }}">@lang('messages.Monthly Report')</a></li>
                    <li><a href="{{ route('yearlyreport.index') }}">@lang('messages.Yearly Report')</a></li>
                </ul>
                @endcanany
            </li>






















@endguest 
 
                            
                                
 
 
                        </ul> 
                    </div> 
                    <!-- Sidebar --> 
                </div> 
            </div> 
            <!-- Left Sidebar End --> 
 
           <br><br>  
           <br><br>  
 
            <!-- ============================================================== --> 
            <!-- Start right Content here --> 
            <!-- ============================================================== --> 
<div class="main-content" data-translate="true"> 
                    @if ($message = Session::get('success')) 
                        <div class="alert alert-success text-center" role="alert"> 
                            {{ $message }} 
                        </div> 
                    @endif 
 
                    @yield('content') 
            <!-- end main content--> 
 
        </div> 
    </div> 
        <!-- END layout-wrapper --> 
         
    
        <!-- Right Sidebar --> 
        <div class="right-bar" data-translate="true"> 
            <div data-simplebar class="h-100"> 
                <div class="rightbar-title d-flex align-items-center px-3 py-4"> 
             
                    <h5 class="m-0 me-2">Settings</h5> 
 
                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto"> 
                        <i class="mdi mdi-close noti-icon"></i> 
                    </a> 
                </div> 
 
                <!-- Settings --> 
                <hr class="mt-0" /> 
                <h6 class="text-center mb-0">Choose Layouts</h6> 
 
                <div class="p-4"> 
                    <div class="mb-2"> 
                        <img src="{{asset('backend/assets/images/layouts/layout-1.jpg')}}" class="img-fluid img-thumbnail" alt="layout-1"> 
                    </div> 
 
                    <div class="form-check form-switch mb-3"> 
                        <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked> 
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label> 
                    </div> 
     
                    <div class="mb-2"> 
                        <img src="{{asset('backend/assets/images/layouts/layout-2.jpg')}}" class="img-fluid img-thumbnail" alt="layout-2"> 
                    </div> 
                    <div class="form-check form-switch mb-3"> 
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch" data-bsStyle="{{asset('backend/assets/css/bootstrap-dark.min.css')}}" data-appStyle="assets/css/app-dark.min.css"> 
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label> 
                    </div> 
     
                    <div class="mb-2"> 
                        <img src="{{asset('backend/assets/images/layouts/layout-3.jpg')}}" class="img-fluid img-thumbnail" alt="layout-3"> 
                    </div> 
                    <div class="form-check form-switch mb-5"> 
                        <input class="form-check-input theme-choice" type="checkbox" id="rtl-mode-switch" data-appStyle="assets/css/app-rtl.min.css')}}"> 
                        <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label> 
                    </div> 
 
             
                </div> 
 
            </div> <!-- end slimscroll-menu--> 
        </div> 
        <!-- /Right-bar --> 
 
        <!-- Right bar overlay--> 
     
        <div class="rightbar-overlay" data-translate="true"></div> 
  
                <footer class="footer"> 
                    <div class="container-fluid"> 
                        <div class="row"> 
                            <div class="col-sm-6"> 
                                <script>document.write(new Date().getFullYear())</script> © Amin Said. 
                            </div> 
                            <div class="col-sm-6"> 
                                <div class="text-sm-end d-none d-sm-block"> 
                                    Crafted  <i class="mdi mdi-heart text-danger"></i> by Amin Said 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                </footer> 
            </div> 
        <!-- JAVASCRIPT --> 
        <script> 
    // Function to disable CSS 
    function disableCSS(duration) { 
        // Disable stylesheets 
        document.getElementById('bootstrap-style').disabled = true; 
        document.getElementById('app-style').disabled = true; 
 
        // Run your code here for a duration 
        setTimeout(() => { 
            // Re-enable stylesheets after the duration 
            document.getElementById('bootstrap-style').disabled = false; 
            document.getElementById('app-style').disabled = false; 
        }, duration); 
    } 
 
    // Call the function with a duration (in milliseconds) 
    disableCSS(5000); // Disables CSS for 5 seconds 
</script> 
 
        <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script> 
 
<!-- apexcharts --> 
<script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}"></script> 
 
<!-- jquery.vectormap map --> 
<script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script> 
 
<!-- Required datatable js --> 
<script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script> 
 
<!-- Responsive examples --> 
<script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script> 
<script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script> 
 
<!-- Dashboard initialization script --> 
<script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script> 
 
<!-- App js --> 
<script src="{{ asset('backend/assets/js/app.js') }}"></script> 
 
    </body> 

</html>