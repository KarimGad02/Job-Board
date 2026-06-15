<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin','employer','candidate'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // create an admin user for convenience
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $adminRole = Role::firstWhere('name','admin');
        if ($adminRole && ! $admin->hasRole('admin')) {
            $admin->roles()->attach($adminRole->id);
        }
    }
}
