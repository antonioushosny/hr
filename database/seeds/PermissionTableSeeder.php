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
           'user_delete',           

           'technical_list',
           'technical _create',
           'technical _edit',
           'technical _delete',

           'service_list',
           'service _create',
           'service _edit',
           'service_delete',

           'subscription_list',
           'subscription_create',
           'subscription_edit',
           'subscription_delete',

           'status_list',
           'status _create',
           'status _edit',
           'status _delete',

           'country_list',
           'country _create',
           'country _edit',
           'country _delete',

           'city_list',
           'city _create',
           'city _edit',
           'city _delete',

           'area_list',
           'area _create',
           'area _edit',
           'area _delete',

           'nationality_list',
           'nationality _create',
           'nationality _edit',
           'nationality _delete',

           'static_page_list',
           'static_page _edit',

           'contact_list',
           'contact_edit',
           'contact_delete',

           'order_list',
           'rate_list',

           'send_message',
           'reports',   
        ];

        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}