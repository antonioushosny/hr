<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Reasons extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [

           'reasons_list',
           'reasons_create',
           'reasons_edit',
           'reasons_delete',
    
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}