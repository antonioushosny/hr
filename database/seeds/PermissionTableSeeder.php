<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
           'role_list', 
           'role_create',
           'role_edit',
           'role_delete',

           'admin_list',
           'admin_create',
           'admin_edit',
           'admin_delete',

           'user_list',
           'user_create',
           'user_edit',

           'technical_list',
           'technical_create',
           'technical_edit',

           'service_list',
           'service_create',
           'service_edit',

           'subscription_list',
           'subscription_create',
           'subscription_edit',
           'subscription_delete',

           'country_list',
           'country_create',
           'country_edit',
           'country_delete',

           'city_list',
           'city_create',
           'city_edit',
           'city_delete',

           'area_list',
           'area_create',
           'area_edit',
           'area_delete',

           'nationality_list',
           'nationality_create',
           'nationality_edit',
           'nationality_delete',

           'static_page_list',
           'static_page_edit',

           'contact_list',
           'contact_edit',
           'contact_delete',

           'order_list',
           'rate_list',

           'send_message',
           'reports',   
        ];

        foreach ($permissions as $permission) {
            if($permission == 'role_list'){
                Permission::create(['name' => $permission,'guard_name' => 'app']);

            }else{
                Permission::create(['name' => $permission]);

            }

        }
    }
}