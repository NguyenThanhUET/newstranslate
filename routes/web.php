<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login','Auth\LoginController@index');
Route::get('/register','Auth\RegisterController@index');
Route::get('/login/google', array('as'=>'user.googleLogin','uses'=>'Auth\LoginController@googleLogin'));
Route::get('/member/profile',array('as'=>'user.user_profile','uses'=>'Member\MemberController@index'));

//test start

//thanhnv add
//Route::get('google', function () {
//    return view('googleAuth');
//});
//Route::get('/auth/google', 'Auth\AuthController@redirectToGoogle');
//Route::get('/auth/google/callback', 'Auth\AuthController@handleGoogleCallback');
//test end