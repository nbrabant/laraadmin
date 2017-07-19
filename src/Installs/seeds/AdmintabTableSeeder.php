<?php

use Illuminate\Database\Seeder;

use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Dwij\Laraadmin\Models\ModuleFieldTypes;
use Dwij\Laraadmin\Models\Menu;


class AdmintabTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Generating Module Menus
        $modules = Module::all();
        $teamMenu = Menu::create([
            "name" => "Team",
            "url" => "#",
            "icon" => "fa-group",
            "type" => 'custom',
            "parent" => 0,
            "hierarchy" => 1
        ]);
        foreach ($modules as $module) {
            $parent = 0;
            if($module->name != "Backups") {
                if(in_array($module->name, ["Users", "Departments", "Employees", "Roles", "Permissions"])) {
                    $parent = $teamMenu->id;
                }
                Menu::create([
                    "name" => $module->name,
                    "url" => $module->name_db,
                    "icon" => $module->fa_icon,
                    "type" => 'module',
                    "parent" => $parent
                ]);
            }
        }
    }
}
