<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Only run RoleSeeder if permissions don't exist yet
        if (Permission::count() === 0) {
            $this->call(RoleSeeder::class);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@retnews.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('Admin@123456'),
                'is_active' => true,
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $editor = User::firstOrCreate(
            ['email' => 'editor@newsmedia.test'],
            [
                'name' => 'Editor User',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        if (!$editor->hasRole('editor')) {
            $editor->assignRole('editor');
        }

        $writer = User::firstOrCreate(
            ['email' => 'writer@newsmedia.test'],
            [
                'name' => 'Writer User',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        if (!$writer->hasRole('writer')) {
            $writer->assignRole('writer');
        }

        $this->call(DistrictSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ContentSeeder::class);
        $this->call(KeywordSeeder::class);
        $this->call(HomePageSettingSeeder::class);
    }
}
