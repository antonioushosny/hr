<?php

use App\Notifications\doctornotify;
use App\User;
// use QRCode;

// namespace App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RoutedealProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('lang/{lang}', function ($lang){
   $locale = $lang;
   session(['lang' => $locale]);
   App::setLocale($lang);
   $lang = App::getlocale();

   return redirect()->back();
})->name('setlang');

Route::get('/', function () {
    return view('landing');
});

Route::get('/admin', function () {
    return redirect('/login');
});


Auth::routes();
// Route::post('reset-password/{token}', 'ResetPasswordController@resetPassword')->name('resetPassword');
Route::group(['middleware' => 'auth'], function () {

    Route::resource('roles','RoleController');
    
    //route public
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile/{id}', 'HomeController@profile')->name('profile');
    Route::post('/profile/editprofile', 'HomeController@editprofile')->name('editprofile');

    // routes for admins management
    Route::get('/admins', 'AdminsController@index')->name('admins');
    Route::get('/admins/add/', 'AdminsController@add')->name('addadmin');
    Route::post('/admins/update/', 'AdminsController@store')->name('storeadmin');
    Route::get('/admins/edit/{id}', 'AdminsController@edit')->name('editadmin');
    Route::get('/admins/delete/{id}', 'AdminsController@destroy')->name('destroyadmin');
    Route::post('/admins/deleteall', 'AdminsController@deleteall')->name('adminsdeleteall');
    
    // routes for cities management
    Route::get('/cities', 'CitiesController@index')->name('cities');
    Route::get('/cities/add/', 'CitiesController@add')->name('addcitie');
    Route::post('/cities/update/', 'CitiesController@store')->name('storecitie');
    Route::get('/cities/edit/{id}', 'CitiesController@edit')->name('editcitie');
    Route::get('/cities/delete/{id}', 'CitiesController@destroy')->name('destroycitie');
    Route::post('/cities/deleteall', 'CitiesController@deleteall')->name('citiesdeleteall');
    Route::get('/cities/{id}/areas', 'CitiesController@areas')->name('cityareas');

    // routes for areas management
    Route::get('/areas', 'AreasController@index')->name('areas');
    Route::get('/areas/add/', 'AreasController@add')->name('addarea');
    Route::post('/areas/update/', 'AreasController@store')->name('storearea');
    Route::get('/areas/edit/{id}', 'AreasController@edit')->name('editarea');
    Route::get('/areas/delete/{id}', 'AreasController@destroy')->name('destroyarea');
    Route::post('/areas/deleteall', 'AreasController@deleteall')->name('areasdeleteall');

    // routes for containers management
    Route::get('/containers', 'ContainersController@index')->name('containers');
    Route::get('/containers/add/', 'ContainersController@add')->name('addcontainer');
    Route::post('/containers/update/', 'ContainersController@store')->name('storecontainer');
    Route::get('/containers/edit/{id}', 'ContainersController@edit')->name('editcontainer');
    Route::get('/containers/delete/{id}', 'ContainersController@destroy')->name('destroycontainer');
    Route::post('/containers/deleteall', 'ContainersController@deleteall')->name('containersdeleteall');
    
    // routes for providers management
    Route::get('/providers', 'ProvidersController@index')->name('providers');
    Route::get('/providers/add/', 'ProvidersController@add')->name('addprovider');
    Route::post('/providers/update/', 'ProvidersController@store')->name('storeprovider');
    Route::get('/providers/edit/{id}', 'ProvidersController@edit')->name('editprovider');
    Route::get('/providers/delete/{id}', 'ProvidersController@destroy')->name('destroyprovider');
    Route::post('/providers/deleteall', 'ProvidersController@deleteall')->name('providersdeleteall');
    Route::get('/providers/{id}/centers', 'ProvidersController@centers')->name('providercenters');

    // routes for centers management
    Route::get('/centers', 'CentersController@index')->name('centers');
    Route::get('/centers/add/', 'CentersController@add')->name('addcenter');
    Route::post('/centers/update/', 'CentersController@store')->name('storecenter');
    Route::get('/centers/edit/{id}', 'CentersController@edit')->name('editcenter');
    Route::get('/centers/delete/{id}', 'CentersController@destroy')->name('destroycenter');
    Route::post('/centers/deleteall', 'CentersController@deleteall')->name('centersdeleteall');

    // routes for drivers management
    Route::get('/drivers', 'DriversController@index')->name('drivers');
    Route::get('/drivers/add/', 'DriversController@add')->name('adddriver');
    Route::post('/drivers/update/', 'DriversController@store')->name('storedriver');
    Route::get('/drivers/edit/{id}', 'DriversController@edit')->name('editdriver');
    Route::get('/drivers/delete/{id}', 'DriversController@destroy')->name('destroydriver');
    Route::post('/drivers/deleteall', 'DriversController@deleteall')->name('driversdeleteall');


    // routes for orders management
    Route::get('/orders', 'OrdersController@index')->name('orders');
    Route::get('/neworders', 'OrdersController@neworders')->name('neworders');
    Route::get('/noworders', 'OrdersController@noworders')->name('noworders');
    Route::get('/lastorders', 'OrdersController@lastorders')->name('lastorders');
    Route::get('/orders/add/', 'OrdersController@add')->name('addorder');
    Route::post('/orders/update/', 'OrdersController@actionfororder')->name('actionfororder');
    Route::post('/orders/assignDriver/', 'OrdersController@assignDriver')->name('assignDriver');
    Route::get('/orders/edit/{id}', 'OrdersController@edit')->name('editorder');
    Route::get('/orders/delete/{id}', 'OrdersController@destroy')->name('destroyorder');
    Route::post('/orders/deleteall', 'OrdersController@deleteall')->name('ordersdeleteall');
    
    // routes for users management
    Route::get('/users', 'UsersController@index')->name('users');
    Route::post('/users/update/', 'UsersController@store')->name('storeuser');
    Route::get('/users/add', 'UsersController@add')->name('adduser');
    Route::get('/users/edit/{id}', 'UsersController@edit')->name('edituser');
    Route::get('/users/userstatus/{id}', 'UsersController@changestatus')->name('userstatus');
    Route::get('/users/delete/{id}', 'UsersController@destroy')->name('destroyuser');
    Route::post('/users/deleteall', 'UsersController@deleteall')->name('usersdeleteall');
    Route::get('/users/orders/{id}', 'UsersController@orders')->name('userorders');

    
    //  routes for contact_us management
    //  routes for contact_us management
    Route::get('/contact_us', 'ContactsController@index')->name('contacts');
    Route::get('/contacts/delete/{id}', 'ContactsController@destroy')->name('destroycontact');
    Route::post('/contacts/deleteall', 'ContactsController@deleteall')->name('contactsdeleteall');


    // routes for reports management
    Route::get('/reports', 'ReportsController@index')->name('reports');
    Route::post('/reports', 'ReportsController@search')->name('reportfilter');
    // Route::get('/reports/reportfilter', 'ReportsController@reportfilter')->name('reportfilter');
    Route::get('/reports/reportdetail/{id}', 'ReportsController@show')->name('reportdetail');
    
    // routes for settings management
    Route::get('/settings/{type}', 'HomeController@settings')->name('settings');
    Route::get('/settings/add/{type}', 'HomeController@add')->name('addsetting');
    Route::post('/settings/store', 'HomeController@store')->name('storesetting');
    Route::get('/settings/edit/{type}/{id}', 'HomeController@edit')->name('editsetting');
    Route::put('/settings/edit/{id}', 'HomeController@editsettings')->name('editsettings');
    Route::get('/settings/delete/{id}', 'HomeController@destroy')->name('destroysetting');
    Route::post('/settings/deleteall', 'HomeController@deleteall')->name('settingsdeleteall');
    Route::post('/editprofile', 'HomeController@editprofile')->name('editprofile');
    
    Route::get('/token/{token}','HomeController@savetoken')->name('savetoken');
    // routes for notifications management
    Route::post('/notification/get','NotificationController@get');
    Route::get('/MarkAllSeen' ,'Controller@AllSeen')->name('MarkAllSeen');
    
     // routes for messages management by Antonios hosny for hala company
     Route::get('/messages', 'HomeController@messages')->name('messages');
     Route::post('/messages/send', 'HomeController@send')->name('send_messages');

    //route for ajax 

});



