<?php

namespace Workdo\DairyCattleManagement\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'DairyCattleManagement';

        $permissions  = [
            'dairycattle manage',
            'animal manage',
            'animal create',
            'animal edit',
            'animal delete',
            'animal show',
            'health manage',
            'health create',
            'health edit',
            'health delete',
            'health show',
            'breeding manage',
            'breeding create',
            'breeding edit',
            'breeding delete',
            'breeding show',
            'weight manage',
            'weight create',
            'weight edit',
            'weight delete',
            'weight show',
            'dailymilksheet manage',
            'dailymilksheet create',
            'dailymilksheet edit',
            'dailymilksheet delete',
            'dailymilksheet show',
            'milkinventory manage',
            'milkinventory create',
            'milkinventory edit',
            'milkinventory delete',
            'milkinventory show',
            'commulativemilksheet manage',
            'commulativemilksheet create',
            'commulativemilksheet edit',
            'commulativemilksheet delete',
            'commulativemilksheet show',
            'milkproduct manage',
            'milkproduct create',
            'milkproduct edit',
            'milkproduct delete',
            'milkproduct show',

            'feeds manage',
            'feed type manage',
            'feed type create',
            'feed type edit',
            'feed type delete',
            'feed schedule manage',
            'feed schedule create',
            'feed schedule edit',
            'feed schedule delete',
            'feed consumption manage',
            'feed consumption create',
            'feed consumption edit',
            'feed consumption delete',
            'vaccination manage',
            'vaccination create',
            'vaccination edit',
            'vaccination delete',
            'sales-distribution manage',
            'sales-distribution create',
            'sales-distribution edit',
            'sales-distribution delete',
            'expense tracking manage',
            'expense tracking create',
            'expense tracking edit',
            'expense tracking delete',
            'birth record manage',
            'birth record create',
            'birth record edit',
            'birth record delete',
            'dairy-equipment manage',
            'dairy-equipment create',
            'dairy-equipment edit',
            'dairy-equipment delete',
        ];


        $company_role = Role::where('name', 'company')->first();
        foreach ($permissions as $key => $value) {
            $check = Permission::where('name', $value)->where('module', $module)->exists();
            if ($check == false) {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if (!$company_role->hasPermission($value)) {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
