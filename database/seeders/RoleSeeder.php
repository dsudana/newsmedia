<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_roles',
            'manage_settings',
            'manage_articles',      // View Any, Delete Any
            'create_articles',
            'edit_articles',        // Edit Any
            'edit_own_articles',
            'publish_articles',
            'delete_articles',
            'manage_categories',
            'manage_tags',
            'manage_comments',
            'manage_ads',
            'create_ads',
            'view_own_ads',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // Admin
        $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        $admin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Editor
        $editor = \Spatie\Permission\Models\Role::create(['name' => 'editor']);
        $editor->givePermissionTo([
            'view_dashboard',
            'manage_articles',
            'create_articles',
            'edit_articles',
            'publish_articles',
            'delete_articles',
            'manage_categories',
            'manage_tags',
            'manage_comments',
        ]);

        // Writer
        $writer = \Spatie\Permission\Models\Role::create(['name' => 'writer']);
        $writer->givePermissionTo([
            'view_dashboard',
            'create_articles',
            'edit_own_articles',
        ]);

        // Advertiser
        $advertiser = \Spatie\Permission\Models\Role::create(['name' => 'advertiser']);
        $advertiser->givePermissionTo([
            'view_dashboard',
            'create_ads',
            'view_own_ads',
        ]);
    }
}
