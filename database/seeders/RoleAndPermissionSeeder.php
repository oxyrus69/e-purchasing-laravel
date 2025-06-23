<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Permissions yang lebih spesifik
        Permission::create(['name' => 'view-dashboard']);
        
        Permission::create(['name' => 'manage-suppliers']);
        Permission::create(['name' => 'manage-products']);

        Permission::create(['name' => 'create-pr']);
        Permission::create(['name' => 'view-pr']);
        Permission::create(['name' => 'approve-pr']);
        Permission::create(['name' => 'reject-pr']);
        
        Permission::create(['name' => 'create-po']);
        Permission::create(['name' => 'view-po']);
        
        Permission::create(['name' => 'create-grn']);
        Permission::create(['name' => 'view-grn']);

        Permission::create(['name' => 'manage-users']);
        
        Permission::create(['name' => 'view-po-report']);
        Permission::create(['name' => 'view-pr-report']);
        Permission::create(['name' => 'view-grn-report']);
        Permission::create(['name' => 'manage-invoices']);
        Permission::create(['name' => 'mark-invoice-paid']);
        
        Permission::create(['name' => 'view-stock']);
        Permission::create(['name' => 'create-stock-requisition']); // <-- Tambahkan ini
        Permission::create(['name' => 'approve-stock-requisition']);
        Permission::create(['name' => 'adjust-stock']);


        // Buat Roles dan berikan permissions
        
        $role_staff = Role::create(['name' => 'Staff']);
        $role_staff->givePermissionTo(['view-dashboard', 'create-pr', 'view-pr', 'create-stock-requisition']);

        $role_manager = Role::create(['name' => 'Manager']);
        $role_manager->givePermissionTo([
            'view-dashboard', 
            'view-pr', 
            'approve-pr', 
            'reject-pr', 
            'view-po-report',
            'view-pr-report',
            'view-grn-report',
            'create-po',
            'view-po',
            'create-grn',
            'view-grn',
            'manage-invoices',
            'mark-invoice-paid'
        ]);
        
        $role_purchasing = Role::create(['name' => 'Purchasing']);
        $role_purchasing->givePermissionTo([
            'view-dashboard',
            'manage-suppliers', 
            'manage-products',
            'view-pr',
            'create-po',
            'view-po',
            'manage-invoices',
            'mark-invoice-paid',
            'view-stock'
        ]);

        $role_gudang = Role::create(['name' => 'Gudang']);
        $role_gudang->givePermissionTo(['view-dashboard', 'view-po', 'create-grn', 'view-grn', 'view-stock', 'create-stock-requisition', 'adjust-stock']);

        // !! PERUBAHAN DI SINI !!
        // Berikan semua izin yang ada ke Admin secara eksplisit
        $role_admin = Role::create(['name' => 'Admin']);
        $role_admin->givePermissionTo(Permission::all());
    }
}