<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Create Users ---
        $admin = User::create([
            'username' => 'admin',
            'password' => Hash::make('admin')
        ]);

        $test = User::create([
            'username' => 'test',
            'password' => Hash::make('test')
        ]);

        // --- Create Profiles ---
        $adminProfile = Profile::create([
            'full_name' => 'Administrator',
            'phone' => '08123456789',
            'address' => 'Admin Street 1',
            'avatar' => 'avatars/default.webp',
            'user_id' => $admin->id
        ]);

        $testProfile = Profile::create([
            'full_name' => 'Test User',
            'phone' => '08129876543',
            'address' => 'Test Lane 2',
            'avatar' => 'avatars/default.webp',
            'user_id' => $test->id 
        ]);

        // --- Permissions & Roles ---
        $menus = ['Dashboard', 'Product', 'Users Management', 'Roles Management'];
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($menus as $menu) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action $menu"]);
            }
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'icon' => 'ShieldUser']);
        $testRole = Role::firstOrCreate(['name' => 'test', 'icon' => 'ShieldUser']);

        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);
        $testRole->syncPermissions($permissions);

        $admin->assignRole($adminRole);
        $test->assignRole($testRole);
    }
}
