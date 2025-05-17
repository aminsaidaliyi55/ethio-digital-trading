<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $FederalAdmin = Role::create(['name' => 'FederalAdmin']);
        $RegionalAdmin = Role::create(['name' => 'RegionalAdmin']);
        $ZoneAdmin = Role::create(['name' => 'ZoneAdmin']);
        $WoredaAdmin = Role::create(['name' => 'WoredaAdmin']);
        $KebeleAdmin = Role::create(['name' => 'KebeleAdmin']);       
        $Owners = Role::create(['name' => 'Owners']);
        $Customer = Role::create(['name' => 'Customer']);

                $admin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
              'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
             'create-permission',
            'edit-permission',
            'delete-permission',
            'federals-create',
            'federals-edit',
            'federals-delete',
            'federals-view'

        ]);
        $Customer->givePermissionTo([
            'view-product',
            'show-shop',
            'order-products',
            'pay-product',
            'add-to-cart',
            'federals-view',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view'
        ]);
        $Owners->givePermissionTo([
            'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
            'federals-view',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view'
        ]);

        $FederalAdmin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
            'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'federals-view',
            'regions-create',
            'regions-edit',
            'regions-delete',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view'
            


        ]);  

        $RegionalAdmin->givePermissionTo([

            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
              'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
             'create-permission',
            'edit-permission',
            'delete-permission',
             'zones-create',
            'zones-edit',
            'zones-delete',
           'federals-view',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view',
            

        ]);  

        $ZoneAdmin->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
              'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
             'create-permission',
            'edit-permission',
            'delete-permission',
            'woredas-create',
            'woredas-edit',
            'woredas-delete',
            'federals-view',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view',


        ]);  

        $WoredaAdmin->givePermissionTo([
'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
              'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
             'create-permission',
            'edit-permission',
            'delete-permission',
            'kebeles-create',
            'kebeles-edit',
            'kebeles-delete',
            'federals-view',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view',


        ]); 

        $KebeleAdmin->givePermissionTo([
             'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-category',
            'edit-category',
            'delete-category',
            'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'federals-view',
            'regions-view',
            'zones-view',
            'woredas-view',
            'kebeles-view'



        ]);

    }
}