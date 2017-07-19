<?php

use Illuminate\Database\Seeder;

use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Dwij\Laraadmin\Models\ModuleFieldTypes;

use App\Role;
use App\Permission;
use App\Models\Department;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dept = Department::create([
            "name"  => "Administration",
            "tags"  => "[]",
            "color" => "#000",
        ]);

        // Generating Module Menus
        $role = Role::create([
            "name"          => "SUPER_ADMIN",
            "display_name"  => "Super Admin",
            "description"   => "Full Access Role",
            "parent"        => 1,
            "dept"          => $dept->id
        ]);

        $modules = Module::all();
        foreach ($modules as $module) {
            Module::setDefaultRoleAccess($module->id, $role->id, "full");
        }

        $perm = Permission::create([
            "name" => "ADMIN_PANEL",
            "display_name" => "Admin Panel",
            "description" => "Admin Panel Permission"
        ]);

        $role->attachPermission($perm);

        foreach ($modules as $module) {
            $module->is_gen=true;
            $module->save();
        }
    }

}
