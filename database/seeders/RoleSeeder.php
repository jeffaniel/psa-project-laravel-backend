<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'staff', 'display_name' => 'Staff'],
            ['name' => 'manager', 'display_name' => 'Manager'],
            ['name' => 'customer', 'display_name' => 'Customer'],
        ] as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
