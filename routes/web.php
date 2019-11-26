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

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/admin', function () {
    return redirect('/login');
});


Auth::routes();
// Route::post('reset-password/{token}', 'ResetPasswordController@resetPassword')->name('resetPassword');
Route::group(['middleware' => 'auth'], function () {

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
    
   
    // routes for hrs management
    Route::get('/hrs', 'HrsController@index')->name('hrs');
    Route::get('/hrs/add/', 'HrsController@add')->name('addhr');
    Route::post('/hrs/update/', 'HrsController@store')->name('storehr');
    Route::get('/hrs/edit/{id}', 'HrsController@edit')->name('edithr');
    Route::get('/hrs/delete/{id}', 'HrsController@destroy')->name('destroyhr');
    Route::post('/hrs/deleteall', 'HrsController@deleteall')->name('hrsdeleteall');

    // routes for employees management
    Route::get('/employees', 'EmployeesController@index')->name('employees');
    Route::get('/employees/add/', 'EmployeesController@add')->name('addemployee');
    Route::post('/employees/update/', 'EmployeesController@store')->name('storeemployee');
    Route::get('/employees/edit/{id}', 'EmployeesController@edit')->name('editemployee');
    Route::get('/employees/view/{id}', 'EmployeesController@view')->name('viewemployee');
    Route::get('/employees/delete/{id}', 'EmployeesController@destroy')->name('destroyemployee');
    Route::post('/employees/deleteall', 'EmployeesController@deleteall')->name('employeesdeleteall');

    // routes for awards management
    Route::get('/employees/awards/{id}', 'EmployeesController@awards')->name('awards');
    Route::get('/employees/awards/add/{id}', 'EmployeesController@addaward')->name('addaward');
    Route::post('/employees/awards/update/', 'EmployeesController@storeaward')->name('storeaward');
    Route::get('/employees/awards/edit/{id}', 'EmployeesController@editaward')->name('editaward');
    Route::get('/employees/awards/delete/{id}', 'EmployeesController@destroyaward')->name('destroyaward');
    Route::post('/employees/awards/deleteall', 'EmployeesController@deleteallaward')->name('awardsdeleteall');
    // routes for discounts management
    Route::get('/employees/discounts/{id}', 'EmployeesController@discounts')->name('discounts');
    Route::get('/employees/discounts/add/{id}', 'EmployeesController@adddiscount')->name('adddiscount');
    Route::post('/employees/discounts/update/', 'EmployeesController@storediscount')->name('storediscount');
    Route::get('/employees/discounts/edit/{id}', 'EmployeesController@editdiscount')->name('editdiscount');
    Route::get('/employees/discounts/delete/{id}', 'EmployeesController@destroydiscount')->name('destroydiscount');
    Route::post('/employees/discounts/deleteall', 'EmployeesController@deletealldiscount')->name('discountsdeleteall');
    
    // routes for departments management
    Route::get('/departments', 'DepartmentsController@index')->name('departments');
    Route::get('/departments/add/', 'DepartmentsController@add')->name('adddepartment');
    Route::post('/departments/update/', 'DepartmentsController@store')->name('storedepartment');
    Route::get('/departments/edit/{id}', 'DepartmentsController@edit')->name('editdepartment');
    Route::get('/departments/delete/{id}', 'DepartmentsController@destroy')->name('destroydepartment');
    Route::post('/departments/deleteall', 'DepartmentsController@deleteall')->name('departmentsdeleteall');
    
    // routes for tasks management
    Route::get('/tasks', 'TasksController@index')->name('tasks');
    Route::get('/tasks/add/', 'TasksController@add')->name('addtask');
    Route::post('/tasks/update/', 'TasksController@store')->name('storetask');
    Route::get('/tasks/edit/{id}', 'TasksController@edit')->name('edittask');
    Route::get('/tasks/delete/{id}', 'TasksController@destroy')->name('destroytask');
    Route::post('/tasks/deleteall', 'TasksController@deleteall')->name('tasksdeleteall');
    
    // routes for attendances management
    Route::get('/attendances', 'AttendancesController@index')->name('attendances');
    Route::get('/attendances/add/', 'AttendancesController@add')->name('addattendance');
    Route::post('/attendances/update/', 'AttendancesController@store')->name('storeattendance');
    Route::get('/attendances/edit/{id}', 'AttendancesController@edit')->name('editattendance');
    Route::get('/attendances/delete/{id}', 'AttendancesController@destroy')->name('destroyattendance');
    Route::post('/attendances/deleteall', 'AttendancesController@deleteall')->name('attendancesdeleteall');

    // routes for vacations management
    Route::get('/vacations', 'VacationsController@index')->name('vacations');
    Route::get('/vacations/accep/{id}', 'VacationsController@accept')->name('acceptvacation');
    Route::get('/vacations/reject/{id}', 'VacationsController@reject')->name('rejectvacation');
    Route::get('/vacations/delete/{id}', 'VacationsController@destroy')->name('destroyvacation');
    Route::post('/vacations/deleteall', 'VacationsController@deleteall')->name('vacationsdeleteall');


    // routes for change management
    Route::get('/changes', 'ChangesController@index')->name('changes');
    Route::get('/changes/accep/{id}', 'ChangesController@accept')->name('acceptchange');
    Route::get('/changes/reject/{id}', 'ChangesController@reject')->name('rejectchange');
    Route::get('/changes/delete/{id}', 'ChangesController@destroy')->name('destroychange');
    Route::post('/changes/deleteall', 'ChangesController@deleteall')->name('changesdeleteall');


    // routes for mac management
    Route::get('/macs', 'MacsController@index')->name('macs');
    Route::get('/macs/accep/{id}', 'MacsController@accept')->name('acceptmac');
    Route::get('/macs/reject/{id}', 'MacsController@reject')->name('rejectmac');
    Route::get('/macs/delete/{id}', 'MacsController@destroy')->name('destroymac');
    Route::post('/macs/deleteall', 'MacsController@deleteall')->name('macsdeleteall');
 
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
    
     // routes for messages management by Antonios hosny for Codex company
     Route::get('/messages', 'HomeController@messages')->name('messages');
     Route::post('/messages/send', 'HomeController@send')->name('send_messages');
     Route::post('/contact_us', 'HomeController@contact_us')->name('contact_us');

    //route for ajax 
    
});



