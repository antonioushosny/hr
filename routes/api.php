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
Route::post('Countries', 'ApiController@Countries')->middleware('localization');
Route::post('Register', 'ApiController@Register')->middleware('localization');
Route::post('EditProfile', 'ApiController@EditProfile')->middleware('localization');
Route::post('Logout', 'ApiController@Logout')->middleware('localization');
Route::post('Services', 'ApiController@Services')->middleware('localization');
Route::post('AllWorkers', 'ApiController@AllWorkers')->middleware('localization');
Route::post('NearstWorkers', 'ApiController@NearstWorkers')->middleware('localization');
Route::post('AvailableWorkers', 'ApiController@AvailableWorkers')->middleware('localization');
Route::post('WorkerDetail', 'ApiController@WorkerDetail')->middleware('localization');
Route::post('Favorite', 'ApiController@Favorite')->middleware('localization');
Route::post('MyFavorites', 'ApiController@MyFavorites')->middleware('localization');
Route::post('RequestOrder', 'ApiController@RequestOrder')->middleware('localization');
Route::post('MyOrders', 'ApiController@MyOrders')->middleware('localization');
Route::post('CancelationReason', 'ApiController@CancelationReason')->middleware('localization');
Route::post('CanceleOrder', 'ApiController@CanceleOrder')->middleware('localization');
Route::post('AcceptOrder', 'ApiController@AcceptOrder')->middleware('localization');
Route::post('DealDone', 'ApiController@DealDone')->middleware('localization');
Route::post('CompleteOrder', 'ApiController@CompleteOrder')->middleware('localization');
Route::post('OrderDetail', 'ApiController@OrderDetail')->middleware('localization');
Route::post('SubscriptionTypes', 'ApiController@SubscriptionTypes')->middleware('localization');
Route::post('RenewSubscription', 'ApiController@RenewSubscription')->middleware('localization');
Route::post('RateFannie', 'ApiController@RateFannie')->middleware('localization');
Route::post('RateUser', 'ApiController@RateUser')->middleware('localization');
Route::post('MakeAvailable', 'ApiController@MakeAvailable')->middleware('localization');


Route::post('ContactUs', 'ApiController@ContactUs')->middleware('localization');
Route::post('TermsConditions', 'ApiController@TermsConditions')->middleware('localization');
Route::post('AboutUs', 'ApiController@AboutUs')->middleware('localization');
Route::post('SocialContacts', 'ApiController@SocialContacts')->middleware('localization');

Route::post('count_notification','ApiController@count_notification')->middleware('localization');
Route::post('get_notification','ApiController@get_notification')->middleware('localization');

Route::post('send_notification','ApiController@send_notification')->middleware('localization');

// for notifications 

Route::Post('make_as_read','ApiController@make_as_read')->middleware('localization');
/////////

//this for test 
Route::Post('send_notifications','ApiController@send_notifications');
Route::Post('webnotifications','ApiController@webnotifications');


