<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'delete tasks']);
        Permission::create(['name' => 'edit tasks']);
        Permission::create(['name' => 'create employee']);
        Permission::create(['name' => 'delete employee']);
        Permission::create(['name' => 'edit employee']);

        // create roles and assing existing permissions
        $role1 = Role::create(['name' => 'employee']);
        $role1->givePermissionTo('edit tasks');
        
        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('create tasks');
        $role2->givePermissionTo('delete tasks');
        $role2->givePermissionTo('edit tasks');
        $role2->givePermissionTo('create employee');
        $role2->givePermissionTo('delete employee');
        $role2->givePermissionTo('edit employee');


        // seed db with users and assign roles
        $users = User::factory(10)->create();

        foreach($users as $user) {
            $user->assignRole($role1);
        }

        $admin = User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin')
        ]);

        $admin->assignRole($role2);



        
    }
}
