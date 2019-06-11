<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/logout','HomeController@doLogout');
// Route::get('/login', 'HomeController@login')->name('login');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin/login', 'AdminController@login')->name('adminLogin');
Route::post('/admin/login', 'AdminController@postLogin')->name('adminPostLogin');
Route::get('/admin/register', 'AdminController@register')->name('adminRegister');
Route::post('/admin/register', 'AdminController@postRegister')->name('adminPostRegister');


Route::get('/admin', 'AdminController@home')->name('adminHome')
    ->middleware('CheckAdmin');

// Route::post('/login/custom',[
//     'uses'=>'Auth\LoginController@login',
//     'as'=>'login.custom'
// ]);
//Route::get('{path}', "HomeController@index")->where('path', '([A-z\d-\/_.]+)?');
//Route::get('{anypath}','HomeController@index')->where('path','.*');
Route::get('/{vue_capture?}', function () {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*');
