<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-role',
            'edit-role',
            'delete-role',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'create-category',
            'edit-category',
            'delete-category',
            'create-user',
            'edit-user',
            'delete-user', 
            'create-shop',
            'edit-shop',
            'show-shop',
            'delete-shop',
            'create-product',
            'edit-product',
            'delete-product',
            'view-product',
            'order-products',
            'pay-product',
            'add-to-cart',
            'federals-create',
            'federals-edit',
            'federals-delete',
            'federals-view',
            'regions-create',
            'regions-edit',
            'regions-view', 
            'regions-delete',
            'zones-view',
            'zones-create',
            'zones-delete',
            'zones-edit',
            'woredas-edit',
            'woredas-create',
            'woredas-delete', 
            'woredas-view', 
            'kebeles-create',
            'kebeles-edit',
            'kebeles-view',
            'kebeles-delete',
            'changeStatus'
         ];
 
          // Looping and Inserting Array's Permissions into Permission Table
         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}