@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New User
                </div>
                <div class="float-end">
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf

                    <!-- Name field -->
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Email field -->
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Password fields -->
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <!-- Roles dropdown -->
                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6">
                        <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles">
    <option value="" disabled selected>Select a role</option>
    @foreach ($allowedRoles as $role)
        <option value="{{ $role }}" {{ old('roles') == $role ? 'selected' : '' }}>{{ $role }}</option>
    @endforeach
</select>


                            @if ($errors->has('roles'))
                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Region federal -->
                    <div class="mb-3 row" id="federalField">
                        <div class="col-md-6">
                            <select class="form-select" id="federal_id" name="federal_id" hidden>
                                @foreach ($federals as $federal)
                                    <option value="{{ $federal->id }}">{{ $federal->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Region dropdown -->
                    <div class="mb-3 row" id="regionField">
                        <div class="col-md-6">
                            <select class="form-select" id="region_id" name="region_id" hidden>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Zone dropdown -->
                    <div class="mb-3 row" id="zoneField">
                        <div class="col-md-6">
                             <select class="form-select" id="zone_id" name="zone_id" hidden>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Woreda dropdown -->
                    <div class="mb-3 row" id="woredaField">
                        <div class="col-md-6">
                           <select class="form-select" id="woreda_id" name="woreda_id" hidden>
                                @foreach ($woredas as $woreda)
                                    <option value="{{ $woreda->id }}">{{ $woreda->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kebele dropdown -->
                    <div class="mb-3 row" id="kebeleField">
                        <div class="col-md-6">
                            <select class="form-select" id="kebele_id" name="kebele_id">
                                <option value="" disabled selected>Select a kebele</option>
                                @foreach ($kebeles as $kebele)
                                    <option value="{{ $kebele->id }}">{{ $kebele->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add User">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to dynamically show/hide fields based on selected role -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rolesSelect = document.getElementById('roles');
        const regionField = document.getElementById('regionField');
        const federField = document.getElementById('federalField');
        const zoneField = document.getElementById('zoneField');
        const woredaField = document.getElementById('woredaField');
        const kebeleField = document.getElementById('kebeleField');
        const zoneSelect = document.getElementById('zone_id');
        const woredaSelect = document.getElementById('woreda_id');
        const kebeleSelect = document.getElementById('kebele_id');

        // Hide/Show fields based on role
        rolesSelect.addEventListener('change', function () {
            const selectedRole = rolesSelect.value;
            if (selectedRole === 'FederalAdmin') 
            {

                regionField.style.display = 'none';
                zoneField.style.display = 'none';
                woredaField.style.display = 'none';
                kebeleField.style.display = 'none';
            } 
            else if (selectedRole === 'RegionalAdmin') 
            {
                regionField.style.display = 'block';
                zoneField.style.display = 'none';
                woredaField.style.display = 'none';
                kebeleField.style.display = 'none';
            } 
            
            else if (selectedRole === 'Customer') 
            {
                regionField.style.display = 'none';
                zoneField.style.display = 'none';
                woredaField.style.display = 'none';
                kebeleField.style.display = 'none';
            } else if (selectedRole === 'Admin') 
            {
                regionField.style.display = 'none';
                zoneField.style.display = 'none';
                woredaField.style.display = 'none';
                kebeleField.style.display = 'none';
            }
              else if (selectedRole === 'Owners') 
            {
                regionField.style.display = 'block';
                zoneField.style.display = 'block';
                woredaField.style.display = 'block';
                kebeleField.style.display = 'block';
            }

             else if (selectedRole === 'ZoneAdmin') {
                regionField.style.display = 'block';
                zoneField.style.display = 'block';
                woredaField.style.display = 'none';
                kebeleField.style.display = 'none';
            } 

   else if (selectedRole === 'WoredaAdmin') {
                regionField.style.display = 'block';
                zoneField.style.display = 'block';
                woredaField.style.display = 'block';
                kebeleField.style.display = 'none';
            }  


             else if (selectedRole === 'KebeleAdmin') {
                regionField.style.display = 'block';
                zoneField.style.display = 'block';
                woredaField.style.display = 'block';
                kebeleField.style.display = 'block';
            } 
            else {
                regionField.style.display = 'block';
                zoneField.style.display = 'block';
                woredaField.style.display = 'block';
                kebeleField.style.display = 'block';
            }
        });

        // Trigger change event on page load to set initial visibility
        rolesSelect.dispatchEvent(new Event('change'));

        // Fetch zones based on the selected region
        document.getElementById('region_id').addEventListener('change', function () {
            const regionId = this.value;
            fetch(`/zones/${regionId}`)
                .then(response => response.json())
                .then(data => {
                    zoneSelect.innerHTML = '<option value="" disabled selected>Select a zone</option>';
                    data.forEach(zone => {
                        zoneSelect.innerHTML += `<option value="${zone.id}">${zone.name}</option>`;
                    });
                });
        });

        // Fetch woredas based on the selected zone
        zoneSelect.addEventListener('change', function () {
            const zoneId = this.value;
            fetch(`/woredas/${zoneId}`)
                .then(response => response.json())
                .then(data => {
                    woredaSelect.innerHTML = '<option value="" disabled selected>Select a woreda</option>';
                    data.forEach(woreda => {
                        woredaSelect.innerHTML += `<option value="${woreda.id}">${woreda.name}</option>`;
                    });
                });
        });

        // Fetch kebeles based on the selected woreda
        woredaSelect.addEventListener('change', function () {
            const woredaId = this.value;
            fetch(`/kebeles/${woredaId}`)
                .then(response => response.json())
                .then(data => {
                    kebeleSelect.innerHTML = '<option value="" disabled selected>Select a kebele</option>';
                    data.forEach(kebele => {
                        kebeleSelect.innerHTML += `<option value="${kebele.id}">${kebele.name}</option>`;
                    });
                });
        });
    });
</script>
@endsection
