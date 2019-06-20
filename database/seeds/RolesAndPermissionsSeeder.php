<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'crud users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'crud clients']);
        Permission::create(['name' => 'view clients']);
        Permission::create(['name' => 'crud portfolios']);
        Permission::create(['name' => 'view portfolios']);
        Permission::create(['name' => 'crud trades']);
        Permission::create(['name' => 'view trades']);

        // create roles and assign created permissions
        $role = Role::create(['name' => 'super-admin', 'id' => 1]);
        $role->givePermissionTo(Permission::all());
        User::find(1)->givePermissionTo(Permission::all());
//        $role = Role::create(['name' => 'admin', 'id' => 5]);
        User::find(1)->assignRole('super-admin');
    }
}
