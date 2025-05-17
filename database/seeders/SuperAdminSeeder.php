<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Amin Supper Admin', 
            'email' => 'aminsaidaliyi55@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Amin Admin', 
            'email' => 'aminsaidaliyi555@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $admin->assignRole('Admin');

        // Creating Federal Admin User
        $FederalAdmin = User::create([
            'name' => 'Amin Federal Admin', 
            'email' => 'aminsaidaliyi66@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $FederalAdmin->assignRole('FederalAdmin');

        // Creating Regional Admin User
        $RegionalAdmin = User::create([
            'name' => 'Amin Regional Admin', 
            'email' => 'aminsaidaliyi666@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $RegionalAdmin->assignRole('RegionalAdmin');

        // Creating Zone Admin User
        $ZoneAdmin = User::create([
            'name' => 'Amin Zone Admin', 
            'email' => 'aminsaidaliyi6666@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $ZoneAdmin->assignRole('ZoneAdmin');

        // Creating Woreda Admin User
        $WoredaAdmin = User::create([
            'name' => 'Amin Woreda Admin', 
            'email' => 'aminsaidaliyi66666@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $WoredaAdmin->assignRole('WoredaAdmin');

        // Creating Kebele Admin User
        $KebeleAdmin = User::create([
            'name' => 'Amin Kebele Admin', 
            'email' => 'aminsaidaliyi666666@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $KebeleAdmin->assignRole('KebeleAdmin');

        // Creating Owner User
        $Owners = User::create([
            'name' => 'Amin Owners', 
            'email' => 'aminsaidaliyi55555@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $Owners->assignRole('Owners');

        // Creating Customer User
        $Customer = User::create([
            'name' => 'Amin Customer', 
            'email' => 'aminsaidaliyi5555@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $Customer->assignRole('Customer');
    }
}
