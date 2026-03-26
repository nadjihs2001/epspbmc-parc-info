<?php

namespace Database\Seeders;

use App\Models\Structure;
use App\Models\User;
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
        $this->call([
            Core\StructuresSeeder::class,
            Core\LocationsSeeder::class,
            Permissions\ModulePermissionsSeeder::class,
        ]);

        $adminEmail = 'admin@example.com';
        $user = User::where('email', $adminEmail)->first();

        if (!$user) {
            $structure = Structure::where('code', 'DG')->first() ?? Structure::first();

            $user = User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'structure_id' => $structure?->id,
                'actif' => true,
            ]);
        }

        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user->assignRole($role);

        // Ensure Super Admin has all permissions
        $role->syncPermissions(Permission::all());
    }
}
