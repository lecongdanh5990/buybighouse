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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('user/profile', 'UserController@profile');
Route::put('user/profile', 'UserController@updateProfile');
Route::resource('user', 'UserController', );

Route::get('purpose/today', 'PurposeController@today');
Route::get('purpose/all', 'PurposeController@getAllPurpose');
Route::get('purpose/uncompleted','PurposeController@getUncompletedPurposes');
Route::get('purpose/{purpose}/statuses','PurposeController@getStatuses');
Route::resource('purpose', 'PurposeController',['except'=>['create','edit']]);

Route::get('purpose/{purpose}/targets/today', 'PurposeTargetsController@today');
Route::resource('purpose.targets','PurposeTargetsController',['only' => ['index']]);

Route::resource('purposes.categories', 'PurposeCategoryController', ['only' => ['index']]);

// Route::resource('purpose.works','PurposeWorksController',
//     ['only' => ['index']]);

Route::get('target/today', 'TargetController@today');
Route::get('target/completed', 'TargetController@getCompleted');
Route::patch('target/make-complete/{id}', 'TargetController@makeComplete');
Route::get('target/tomorrow', 'TargetController@tomorrow');
Route::get('target/in-week', 'TargetController@inWeek');
Route::get('target/in-month', 'TargetController@inMonth');
Route::resource('target', 'TargetController',['except'=>['create','edit']]);
//Route::resource('target.statuses','TargetStatusesController',['only' => ['index']]);

Route::put('status/tomato/{id}', 'StatusController@updateTomato');
Route::get('status/today', 'StatusController@getToday');
Route::get('status/in-week', 'StatusController@getInWeek');
Route::get('status/in-month', 'StatusController@getInMonth');
Route::get('status/{month}/{year}', 'StatusController@getThisMonth');
Route::get('status/limited-month', 'StatusController@getLimitedMonth');
Route::resource('status','StatusController',['except' => ['create ', 'show']]);

// Route::get('prodomo/getproofthisday/{day}', 'ProdomoController@getProOfThisDay');
// Route::resource('prodomo', 'ProdomoController', ['except' => ['create ', 'show']]);

Route::get('admin/user', 'AdminController@user');

Route::resource('test','TestController',
    ['except' => ['create', 'edit']]
);

