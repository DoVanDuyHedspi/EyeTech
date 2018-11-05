<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'api-admin',
            'store',
            'branch',
            'detection',
        ];

        foreach ($roles as $role) {
            $r = Role::create(['name' => $role]);
            if ($role == 'api-admin') {
                $r->givePermissionTo(Permission::all());
            } elseif ($role == 'detection') {
                $r->givePermissionTo([
                    'store-list',
                    'vector-id-list',
                    'create-detection'
                ]);
            }
        }
    }
}
