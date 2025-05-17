<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
public function getZones($regionId)
{
    $zones = Zone::where('region_id', $regionId)->get();
    return response()->json($zones);
}

public function getWoredas($zoneId)
{
    $woredas = Woreda::where('zone_id', $zoneId)->get();
    return response()->json($woredas);
}

public function getKebeles($woredaId)
{
    $kebeles = Kebele::where('woreda_id', $woredaId)->get();
    return response()->json($kebeles);
}

   //
}
