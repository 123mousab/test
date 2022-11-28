<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->seedSecondPermissions();
        $this->seedRoles();
        $roles = Role::query()->whereIn('id', [1,2,3,4,5])->pluck('name');

        Admin::query()->create([
            'name' => 'admin',
            'email' => 'super@admin.com',
            'password' => bcrypt(123456)
        ]);

        $admin = Admin::query()->where('id', 1)->first();
        $admin->assignRole($roles);

//        $permissions = Permission::query()->whereIn('id', [1,2,3,4])->pluck('name');
//        $role = Role::query()->where('id', 1)->first();
//        $role->syncPermissions($permissions);
        // \App\Models\User::factory(10)->create();
//        $this->call(GroupSeeder::class);
        /* Admin::query()->create([
             'name' => 'admin',
             'email' => 'super@admin.com',
             'password' => bcrypt(123456),
         ]);*/

        /* Admin::query()->create([
             'name' => 'Owner',
             'email' => 'owner@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Manager',
             'email' => 'manager@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Admin',
             'email' => 'admin@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Chef',
             'email' => 'chef@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Accountants',
             'email' => 'accountants@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Sales',
             'email' => 'sales@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'CService',
             'email' => 'cService@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Packing',
             'email' => 'packing@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Dispatcher',
             'email' => 'dispatcher@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Driver1',
             'email' => 'driver1@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Driver2',
             'email' => 'driver2@admin.com',
             'password' => bcrypt(123456),
         ]);

         Admin::query()->create([
             'name' => 'Driver3',
             'email' => 'driver3@admin.com',
             'password' => bcrypt(123456),
         ]);*/


    }

    protected function seedSecondPermissions(){
        $permissions = [
            'group1',
            'group2',
            'group3',
            'group4',
            'group5',
            'group6',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

    }

    protected function seedRoles(){
        $roles = [
            'constant',
            'store',
            'orders',
            'reports',
            'users',
            'imports',
        ];


        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'admin']);
        }

    }
}
