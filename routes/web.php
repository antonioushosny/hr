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

// Route::get('/', function () {
//     return view('landing');
// });

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/admin', function () {
    return redirect('/login');
});


Auth::routes();
// Route::post('reset-password/{token}', 'ResetPasswordController@resetPassword')->name('resetPassword');
Route::group(['middleware' => 'auth'], function () {

    Route::resource('roles','RoleController')->middleware('permission:role_list');
    Route::get('/roles/delete/{id}', 'RoleController@destroy')->name('destroyrole')->middleware('permission:role_delete');
    Route::post('/roles/deleteall', 'RoleController@deleteall')->name('rolesdeleteall')->middleware('permission:role_delete');
    
    //route public
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile/{id}', 'HomeController@profile')->name('profile');
    Route::post('/profile/editprofile', 'HomeController@editprofile')->name('editprofile');

    // routes for admins management
    Route::get('/admins', 'AdminsController@index')->name('admins')->middleware('permission:admin_list');
    Route::get('/admins/add/', 'AdminsController@add')->name('addadmin')->middleware('permission:admin_create');
    Route::post('/admins/update/', 'AdminsController@store')->name('storeadmin')->middleware('permission:admin_create');
    Route::get('/admins/edit/{id}', 'AdminsController@edit')->name('editadmin')->middleware('permission:admin_edit');
    Route::get('/admins/delete/{id}', 'AdminsController@destroy')->name('destroyadmin')->middleware('permission:admin_delete');
    Route::post('/admins/deleteall', 'AdminsController@deleteall')->name('adminsdeleteall')->middleware('permission:admin_delete');
    
    // routes for countries management
    Route::get('/countries', 'CountriesController@index')->name('countries')->middleware('permission:country_list');
    Route::get('/countries/add/', 'CountriesController@add')->name('addcountrie')->middleware('permission:country_create');
    Route::post('/countries/update/', 'CountriesController@store')->name('storecountrie')->middleware('permission:country_create');
    Route::get('/countries/edit/{id}', 'CountriesController@edit')->name('editcountrie')->middleware('permission:country_edit');
    Route::get('/countries/delete/{id}', 'CountriesController@destroy')->name('destroycountrie')->middleware('permission:country_delete');
    Route::post('/countries/deleteall', 'CountriesController@deleteall')->name('countriesdeleteall')->middleware('permission:country_delete');
    Route::get('/countries/{id}/areas', 'CountriesController@areas')->name('countryareas');

    // routes for cities management
    Route::get('/cities', 'CitiesController@index')->name('cities')->middleware('permission:city_list');
    Route::get('/cities/add/', 'CitiesController@add')->name('addcitie')->middleware('permission:city_create');
    Route::post('/cities/update/', 'CitiesController@store')->name('storecitie')->middleware('permission:city_create');
    Route::get('/cities/edit/{id}', 'CitiesController@edit')->name('editcitie')->middleware('permission:city_edit');
    Route::get('/cities/delete/{id}', 'CitiesController@destroy')->name('destroycitie')->middleware('permission:city_delete');
    Route::post('/cities/deleteall', 'CitiesController@deleteall')->name('citiesdeleteall')->middleware('permission:city_delete');
    Route::get('/cities/{id}/areas', 'CitiesController@areas')->name('cityareas');

    // routes for areas management
    Route::get('/areas', 'AreasController@index')->name('areas')->middleware('permission:area_list');
    Route::get('/areas/add/', 'AreasController@add')->name('addarea')->middleware('permission:area_create');
    Route::post('/areas/update/', 'AreasController@store')->name('storearea')->middleware('permission:area_create');
    Route::get('/areas/edit/{id}', 'AreasController@edit')->name('editarea')->middleware('permission:area_edit');
    Route::get('/areas/delete/{id}', 'AreasController@destroy')->name('destroyarea')->middleware('permission:area_delete');
    Route::post('/areas/deleteall', 'AreasController@deleteall')->name('areasdeleteall')->middleware('permission:area_delete');

    // routes for nationalities management
    Route::get('/nationalities', 'NationalitiesController@index')->name('nationalities')->middleware('permission:nationality_list');
    Route::get('/nationalities/add/', 'NationalitiesController@add')->name('addnationalitie')->middleware('permission:nationality_create');
    Route::post('/nationalities/update/', 'NationalitiesController@store')->name('storenationalitie')->middleware('permission:nationality_create');
    Route::get('/nationalities/edit/{id}', 'NationalitiesController@edit')->name('editnationalitie')->middleware('permission:nationality_edit');
    Route::get('/nationalities/delete/{id}', 'NationalitiesController@destroy')->name('destroynationalitie')->middleware('permission:nationality_delete');
    Route::post('/nationalities/deleteall', 'NationalitiesController@deleteall')->name('nationalitiesdeleteall')->middleware('permission:nationality_delete');
    Route::get('/nationalities/{id}/areas', 'NationalitiesController@areas')->name('nationalityareas');

    // routes for services management
    Route::get('/services', 'ServicesController@index')->name('services')->middleware('permission:service_list');
    Route::get('/services/add/', 'ServicesController@add')->name('addservice')->middleware('permission:service_create');
    Route::post('/services/update/', 'ServicesController@store')->name('storeservice')->middleware('permission:service_create');
    Route::get('/services/edit/{id}', 'ServicesController@edit')->name('editservice')->middleware('permission:service_edit');
    Route::get('/services/delete/{id}', 'ServicesController@destroy')->name('destroyservice')->middleware('permission:service_edit');
    Route::post('/services/deleteall', 'ServicesController@deleteall')->name('servicesdeleteall')->middleware('permission:service_edit');
    Route::get('/services/{id}/areas', 'ServicesController@areas')->name('serviceareas')
    Route::get('/services/technicians/{id}', 'ServicesController@showtechnicians')->name('serviceshowtechnicians');
    

    // routes for reasons management
    Route::get('/reasons', 'ReasonController@index')->name('reasons')->middleware('permission:reasons_list');
    Route::get('/reasons/add/', 'ReasonController@add')->name('addreason')->middleware('permission:reasons_create');
    Route::post('/reasons/update/', 'ReasonController@store')->name('storereason')->middleware('permission:reasons_create');
    Route::get('/reasons/status/{id}', 'ReasonController@changestatus')->name('reasonstatus');
    Route::get('/reasons/edit/{id}', 'ReasonController@edit')->name('editreason')->middleware('permission:reasons_edit');
    // Route::get('/services/delete/{id}', 'ReasonController@destroy')->name('destroyservice');
    // Route::post('/services/deleteall', 'ReasonController@deleteall')->name('servicesdeleteall');
    // Route::get('/services/{id}/areas', 'ReasonController@areas')->name('serviceareas');


    //route for subscriptions management
    Route::get('/subscriptions', 'SubscriptionTypeController@index')->name('subscriptions')->middleware('permission:subscription_type_list');
    Route::get('/subscriptions/add/', 'SubscriptionTypeController@add')->name('addsubscription')->middleware('permission:subscription_type_create');
    Route::post('/subscriptions/update/', 'SubscriptionTypeController@store')->name('storeaddsubscription')->middleware('permission:subscription_type_create');
    Route::get('/subscriptions/edit/{id}', 'SubscriptionTypeController@edit')->name('editsubscription')->middleware('permission:subscription_type_edit');
    Route::get('/subscriptions/status/{id}', 'SubscriptionTypeController@changestatus')->name('subscriptionstatus') ;
    Route::get('/subscriptions/delete/{id}', 'SubscriptionTypeController@destroy')->name('destroysubscription')->middleware('permission:subscription_type_delete');
    Route::post('/subscriptions/deleteall', 'SubscriptionTypeController@deleteall')->name('subscriptionsdeleteall')->middleware('permission:subscription_type_delete');

    // routes for users management
    Route::get('/users', 'UsersController@index')->name('users')->middleware('permission:user_list');
    Route::post('/users/update/', 'UsersController@store')->name('storeuser')->middleware('permission:user_create');
    Route::get('/users/add', 'UsersController@add')->name('adduser')->middleware('permission:user_create');
    Route::get('/users/edit/{id}', 'UsersController@edit')->name('edituser')->middleware('permission:user_edit');
    Route::get('/users/userstatus/{id}', 'UsersController@changestatus')->name('userstatus');
    Route::get('/users/delete/{id}', 'UsersController@destroy')->name('destroyuser')->middleware('permission:user_delete');
    Route::post('/users/deleteall', 'UsersController@deleteall')->name('usersdeleteall')->middleware('permission:user_delete');
    Route::get('/users/orders/{id}', 'UsersController@orders')->name('userorders');
    Route::get('/users/ratings/{id}','UsersController@ratings')->name('usersratings');

    //technicians
    Route::get('/technicians', 'TechniciansController@index')->name('technicians')->middleware('permission:technical_list');
    Route::post('/technicians/update/', 'TechniciansController@store')->name('storetechnician')->middleware('permission:technical_create');
    Route::get('/technicians/add', 'TechniciansController@add')->name('addtechnician')->middleware('permission:technical_create');
    Route::get('/technicians/edit/{id}', 'TechniciansController@edit')->name('edittechnician')->middleware('permission:technical_edit');
    Route::get('/technicians/techicianstatus/{id}', 'TechniciansController@changestatus')->name('techicianstatus');
    Route::get('/technicians/orders/{id}', 'TechniciansController@orders')->name('techniciansorders');
    Route::get('/technicians/maps','TechniciansController@maps')->name('techniciansmaps');
    Route::get('/technicians/ratings/{id}','TechniciansController@ratings')->name('techniciansratings');
    // Route::get('/users/delete/{id}', 'TechniciansController@destroy')->name('destroyuser');
    // Route::post('/users/deleteall', 'TechniciansController@deleteall')->name('usersdeleteall');

    //route for subscriptions technicians management
    Route::get('/technicians/subscriptions', 'SubscriptionController@index')->name('techsubscriptions')->middleware('permission:subscription_list');
     Route::get('/technicians/subscriptions/add', 'SubscriptionController@add')->name('addtechsubscription')->middleware('permission:subscription_create');
     Route::post('/technicians/subscriptions/update/', 'SubscriptionController@store')->name('storeaddtechsubscription')->middleware('permission:subscription_create');
     Route::get('/technicians/subscriptions/edit/{id}', 'SubscriptionController@edit')->name('edittechsubscription')->middleware('permission:subscription_edit');
     Route::get('/technicians/subscriptions/accept/{id}', 'SubscriptionController@accept')->name('accepttechsubscription');
     Route::get('/technicians/subscriptions/reject/{id}', 'SubscriptionController@reject')->name('rejecttechsubscription');
    // Route::get('/subscriptions/delete/{id}', 'SubscriptionController@destroy')->name('destroysubscription');
    // Route::post('/subscriptions/deleteall', 'SubscriptionController@deleteall')->name('subscriptionsdeleteall');


      // routes for orders management
      Route::get('/orders', 'OrderController@index')->name('orders')->middleware('permission:order_list');
     Route::get('/orders/details/{id}', 'OrderController@show')->name('ordersdetails')->middleware('permission:order_list');
    //   Route::post('/orders/update/', 'OrderController@store')->name('storenationalitie');
    //   Route::get('/orders/edit/{id}', 'OrderController@edit')->name('editnationalitie');
    //   Route::get('/orders/delete/{id}', 'OrderController@destroy')->name('destroynationalitie');
    //   Route::post('/orders/deleteall', 'OrderController@deleteall')->name('ordersdeleteall');
    //   Route::get('/orders/{id}/areas', 'OrderController@areas')->name('nationalityareas');

    //  routes for contact_us management
    Route::get('/contact_us', 'ContactsController@index')->name('contacts')->middleware('permission:contact_list');
    Route::get('/contacts/delete/{id}', 'ContactsController@destroy')->name('destroycontact')->middleware('permission:contact_delete');
    Route::post('/contacts/deleteall', 'ContactsController@deleteall')->name('contactsdeleteall')->middleware('permission:contact_delete');

 
    // routes for reports management
    Route::get('/reports', 'ReportsController@index')->name('reports')->middleware('permission:reports');
    Route::post('/reports', 'ReportsController@search')->name('reportfilter')->middleware('permission:reports');
    // Route::get('/reports/reportfilter', 'ReportsController@reportfilter')->name('reportfilter');
    Route::get('/reports/reportdetail/{id}', 'ReportsController@show')->name('reportdetail')->middleware('permission:reports');
    
    // routes for settings management
    Route::get('/settings/{type}', 'HomeController@settings')->name('settings')->middleware('permission:static_page_list');
    Route::get('/settings/add/{type}', 'HomeController@add')->name('addsetting')->middleware('permission:static_page_edit');
    Route::post('/settings/store', 'HomeController@store')->name('storesetting')->middleware('permission:static_page_edit');
    Route::get('/settings/edit/{type}/{id}', 'HomeController@edit')->name('editsetting')->middleware('permission:static_page_edit');
    Route::put('/settings/edit/{id}', 'HomeController@editsettings')->name('editsettings')->middleware('permission:static_page_edit');
    Route::get('/settings/delete/{id}', 'HomeController@destroy')->name('destroysetting')->middleware('permission:static_page_edit');
    Route::post('/settings/deleteall', 'HomeController@deleteall')->name('settingsdeleteall')->middleware('permission:static_page_edit');
    Route::post('/editprofile', 'HomeController@editprofile')->name('editprofile');
    
    Route::get('/token/{token}','HomeController@savetoken')->name('savetoken');
    // routes for notifications management
    Route::post('/notification/get','NotificationController@get');
    Route::get('/MarkAllSeen' ,'Controller@AllSeen')->name('MarkAllSeen');
    
     // routes for messages management by Antonios hosny for Codex company
     Route::get('/messages', 'HomeController@messages')->name('messages')->middleware('permission:send_message');
     Route::post('/messages/send', 'HomeController@send')->name('send_messages')->middleware('permission:send_message');
     Route::post('/contact_us', 'HomeController@contact_us')->name('contact_us');

    //route for ajax 
    
});



