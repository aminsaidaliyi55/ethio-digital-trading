<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Federal;
use App\Models\Region;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use App\Models\User;

class AssignAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admins to federals, regions, zones, woredas, and kebeles based on user roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Assign FederalAdmin to Federals
        $federals = Federal::all();
        foreach ($federals as $federal) {
            $federalAdmin = User::where('federal_id', $federal->id)->whereHas('roles', function($q) {
                $q->where('name', 'FederalAdmin');
            })->first();
            
            if ($federalAdmin) {
                $federal->update(['admin_id' => $federalAdmin->id]);
            }
        }

        // Assign Regionaladmin to Regions
        $regions = Region::all();
        foreach ($regions as $region) {
            $regionalAdmin = User::where('region_id', $region->id)->whereHas('roles', function($q) {
                $q->where('name', 'RegionalAdmin');
            })->first();
            
            if ($regionalAdmin) {
                $region->update(['admin_id' => $regionalAdmin->id]);
            }
        }

        // Assign Zoneadmin to Zones
        $zones = Zone::all();
        foreach ($zones as $zone) {
            $zoneAdmin = User::where('zone_id', $zone->id)->whereHas('roles', function($q) {
                $q->where('name', 'ZoneAdmin');
            })->first();
            
            if ($zoneAdmin) {
                $zone->update(['admin_id' => $zoneAdmin->id]);
            }
        }

        // Assign Woredaadmin to Woredas
        $woredas = Woreda::all();
        foreach ($woredas as $woreda) {
            $woredaAdmin = User::where('woreda_id', $woreda->id)->whereHas('roles', function($q) {
                $q->where('name', 'WoredaAdmin');
            })->first();
            
            if ($woredaAdmin) {
                $woreda->update(['admin_id' => $woredaAdmin->id]);
            }
        }

        // Assign Kebeleadmin to Kebeles
        $kebeles = Kebele::all();
        foreach ($kebeles as $kebele) {
            $kebeleAdmin = User::where('kebele_id', $kebele->id)->whereHas('roles', function($q) {
                $q->where('name', 'KebeleAdmin');
            })->first();
            
            if ($kebeleAdmin) {
                $kebele->update(['admin_id' => $kebeleAdmin->id]);
            }
        }

        $this->info('Admin assignment process completed.');
    }
}
