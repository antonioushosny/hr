<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SubscriptionType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [

           'subscription_type_list',
           'subscription_type_create',
           'subscription_type_edit',
    
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}