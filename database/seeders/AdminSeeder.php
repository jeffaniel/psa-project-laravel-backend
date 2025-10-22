<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Role, Permission};
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator', 'description' => 'Full system access']
        );

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Customer', 'description' => 'Customer access']
        );

        $staffRole = Role::firstOrCreate(
            ['name' => 'staff'],
            ['display_name' => 'Staff', 'description' => 'Staff member access']
        );

        // Create permissions
        $permissions = [
            ['name' => 'manage_products', 'display_name' => 'Manage Products'],
            ['name' => 'manage_orders', 'display_name' => 'Manage Orders'],
            ['name' => 'manage_users', 'display_name' => 'Manage Users'],
            ['name' => 'manage_categories', 'display_name' => 'Manage Categories'],
            ['name' => 'view_reports', 'display_name' => 'View Reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['display_name' => $permission['display_name'], 'description' => '']
            );
        }

        // Assign all permissions to admin role
        $adminRole->permissions()->sync(Permission::all());

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@storeapp.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        // Assign admin role
        if (!$admin->roles()->where('role_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@storeapp.com');
        $this->command->info('Password: password');
    }
}
