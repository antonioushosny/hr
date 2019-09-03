<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('IsRegistered', 'ApiController@IsRegistered')->middleware('localization');
Route::post('SendCode', 'ApiController@SendCode')->middleware('localization');
Route::post('Login', 'ApiController@Login')->middleware('localization');
Route::get('Countries', 'ApiController@Countries')->middleware('localization');
Route::post('Register', 'ApiController@Register')->middleware('localization');
Route::post('EditProfile', 'ApiController@EditProfile')->middleware('localization');
Route::get('Logout', 'ApiController@Logout')->middleware('localization');
Route::get('Services', 'ApiController@Services')->middleware('localization');


Route::post('HomePage', 'ApiController@HomePage')->middleware('localization');
Route::post('MakeOrder', 'ApiController@MakeOrder')->middleware('localization');
Route::post('MyOrders', 'ApiController@MyOrders')->middleware('localization');
Route::post('OrdersHistory', 'ApiController@OrdersHistory')->middleware('localization');
Route::post('CanceleOrder', 'ApiController@CanceleOrder')->middleware('localization');
Route::post('ChangeStatusOrders', 'ApiController@ChangeStatusOrders')->middleware('localization');
Route::post('ContactUs', 'ApiController@ContactUs')->middleware('localization');
Route::get('TermsConditions', 'ApiController@TermsConditions')->middleware('localization');
Route::get('AboutUs', 'ApiController@AboutUs')->middleware('localization');
Route::post('SocialContacts', 'ApiController@SocialContacts')->middleware('localization');


Route::get('count_notification','ApiController@count_notification')->middleware('localization');
Route::get('get_notification','ApiController@get_notification')->middleware('localization');

Route::Post('send_notification','ApiController@send_notification')->middleware('localization');

// for notifications 

Route::Post('make_as_read','ApiController@make_as_read')->middleware('localization');
/////////

//this for test 
Route::Post('send_notifications','ApiController@send_notifications');
Route::Post('webnotifications','ApiController@webnotifications');


