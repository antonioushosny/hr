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

    Route::resource('roles','RoleController');
    Route::get('/roles/delete/{id}', 'RoleController@destroy')->name('destroyrole');
    Route::post('/roles/deleteall', 'RoleController@deleteall')->name('rolesdeleteall');
    
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
    
    // routes for countries management
    Route::get('/countries', 'CountriesController@index')->name('countries');
    Route::get('/countries/add/', 'CountriesController@add')->name('addcountrie');
    Route::post('/countries/update/', 'CountriesController@store')->name('storecountrie');
    Route::get('/countries/edit/{id}', 'CountriesController@edit')->name('editcountrie');
    Route::get('/countries/delete/{id}', 'CountriesController@destroy')->name('destroycountrie');
    Route::post('/countries/deleteall', 'CountriesController@deleteall')->name('countriesdeleteall');
    Route::get('/countries/{id}/areas', 'CountriesController@areas')->name('countryareas');

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

    // routes for nationalities management
    Route::get('/nationalities', 'NationalitiesController@index')->name('nationalities');
    Route::get('/nationalities/add/', 'NationalitiesController@add')->name('addnationalitie');
    Route::post('/nationalities/update/', 'NationalitiesController@store')->name('storenationalitie');
    Route::get('/nationalities/edit/{id}', 'NationalitiesController@edit')->name('editnationalitie');
    Route::get('/nationalities/delete/{id}', 'NationalitiesController@destroy')->name('destroynationalitie');
    Route::post('/nationalities/deleteall', 'NationalitiesController@deleteall')->name('nationalitiesdeleteall');
    Route::get('/nationalities/{id}/areas', 'NationalitiesController@areas')->name('nationalityareas');

    // routes for services management
    Route::get('/services', 'ServicesController@index')->name('services');
    Route::get('/services/add/', 'ServicesController@add')->name('addservice');
    Route::post('/services/update/', 'ServicesController@store')->name('storeservice');
    Route::get('/services/edit/{id}', 'ServicesController@edit')->name('editservice');
    Route::get('/services/delete/{id}', 'ServicesController@destroy')->name('destroyservice');
    Route::post('/services/deleteall', 'ServicesController@deleteall')->name('servicesdeleteall');
    Route::get('/services/{id}/areas', 'ServicesController@areas')->name('serviceareas');
    Route::get('/services/technicians/{id}', 'ServicesController@showtechnicians')->name('serviceshowtechnicians');
    

    // routes for reasons management
    Route::get('/reasons', 'ReasonController@index')->name('reasons');
    Route::get('/reasons/add/', 'ReasonController@add')->name('addreason');
    Route::post('/reasons/update/', 'ReasonController@store')->name('storereason');
    Route::get('/reasons/status/{id}', 'ReasonController@changestatus')->name('reasonstatus');
    Route::get('/reasons/edit/{id}', 'ReasonController@edit')->name('editreason');
    // Route::get('/services/delete/{id}', 'ReasonController@destroy')->name('destroyservice');
    // Route::post('/services/deleteall', 'ReasonController@deleteall')->name('servicesdeleteall');
    // Route::get('/services/{id}/areas', 'ReasonController@areas')->name('serviceareas');


    //route for subscriptions management
    Route::get('/subscriptions', 'SubscriptionTypeController@index')->name('subscriptions');
    Route::get('/subscriptions/add/', 'SubscriptionTypeController@add')->name('addsubscription');
    Route::post('/subscriptions/update/', 'SubscriptionTypeController@store')->name('storeaddsubscription');
    Route::get('/subscriptions/edit/{id}', 'SubscriptionTypeController@edit')->name('editsubscription');
    Route::get('/subscriptions/status/{id}', 'SubscriptionTypeController@changestatus')->name('subscriptionstatus');
    Route::get('/subscriptions/delete/{id}', 'SubscriptionTypeController@destroy')->name('destroysubscription');
    Route::post('/subscriptions/deleteall', 'SubscriptionTypeController@deleteall')->name('subscriptionsdeleteall');

    // routes for users management
    Route::get('/users', 'UsersController@index')->name('users');
    Route::post('/users/update/', 'UsersController@store')->name('storeuser');
    Route::get('/users/add', 'UsersController@add')->name('adduser');
    Route::get('/users/edit/{id}', 'UsersController@edit')->name('edituser');
    Route::get('/users/userstatus/{id}', 'UsersController@changestatus')->name('userstatus');
    Route::get('/users/delete/{id}', 'UsersController@destroy')->name('destroyuser');
    Route::post('/users/deleteall', 'UsersController@deleteall')->name('usersdeleteall');
    Route::get('/users/orders/{id}', 'UsersController@orders')->name('userorders');

    //technicians
    Route::get('/technicians', 'TechniciansController@index')->name('technicians');
    Route::post('/technicians/update/', 'TechniciansController@store')->name('storetechnician');
     Route::get('/technicians/add', 'TechniciansController@add')->name('addtechnician');
     Route::get('/technicians/edit/{id}', 'TechniciansController@edit')->name('edittechnician');
     Route::get('/technicians/techicianstatus/{id}', 'TechniciansController@changestatus')->name('techicianstatus');
      Route::get('/technicians/orders/{id}', 'TechniciansController@orders')->name('techniciansorders');
      Route::get('/technicians/maps','TechniciansController@maps')->name('techniciansmaps');
    // Route::get('/users/delete/{id}', 'TechniciansController@destroy')->name('destroyuser');
    // Route::post('/users/deleteall', 'TechniciansController@deleteall')->name('usersdeleteall');

    //route for subscriptions technicians management
    Route::get('/technicians/subscriptions', 'SubscriptionController@index')->name('techsubscriptions');
     Route::get('/technicians/subscriptions/add', 'SubscriptionController@add')->name('addtechsubscription');
     Route::post('/technicians/subscriptions/update/', 'SubscriptionController@store')->name('storeaddtechsubscription');
     Route::get('/technicians/subscriptions/edit/{id}', 'SubscriptionController@edit')->name('edittechsubscription');
     Route::get('/technicians/subscriptions/accept/{id}', 'SubscriptionController@accept')->name('accepttechsubscription');
     Route::get('/technicians/subscriptions/reject/{id}', 'SubscriptionController@reject')->name('rejecttechsubscription');
    // Route::get('/subscriptions/delete/{id}', 'SubscriptionController@destroy')->name('destroysubscription');
    // Route::post('/subscriptions/deleteall', 'SubscriptionController@deleteall')->name('subscriptionsdeleteall');


      // routes for orders management
      Route::get('/orders', 'OrderController@index')->name('orders');
     Route::get('/orders/details/{id}', 'OrderController@show')->name('ordersdetails');
    //   Route::post('/orders/update/', 'OrderController@store')->name('storenationalitie');
    //   Route::get('/orders/edit/{id}', 'OrderController@edit')->name('editnationalitie');
    //   Route::get('/orders/delete/{id}', 'OrderController@destroy')->name('destroynationalitie');
    //   Route::post('/orders/deleteall', 'OrderController@deleteall')->name('ordersdeleteall');
    //   Route::get('/orders/{id}/areas', 'OrderController@areas')->name('nationalityareas');

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
     Route::post('/contact_us', 'HomeController@contact_us')->name('contact_us');

    //route for ajax 
    
});



