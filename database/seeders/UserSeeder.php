<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Akun Admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // Membuat Akun Manager
        $manager = User::create([
            'name' => 'Manajer Pembelian',
            'username' => 'manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('Manager');

        // Membuat Akun Staf Purchasing
        $purchasing = User::create([
            'name' => 'Staf Purchasing',
            'username' => 'purchasing',
            'email' => 'purchasing@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $purchasing->assignRole('Purchasing');

        // Membuat Akun Staf Biasa
        $staff = User::create([
            'name' => 'Staff',
            'username' => 'staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('Staff');
        
        // Membuat Akun Staf Gudang
        $gudang = User::create([
            'name' => 'Gudang',
            'username' => 'gudang',
            'email' => 'gudang@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $gudang->assignRole('Gudang');
    }
}