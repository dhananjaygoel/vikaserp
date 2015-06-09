<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
//Route::get('login',function(){
//    return 'auth.login';
//});
Route::group(['middleware' => ['auth']], function() {
    Route::resource('security', 'SecurityController');
    Route::get('dashboard', function() {
        return view('home');
    });
    Route::resource('unit', 'UnitController');
    Route::resource('states', 'StatesController');
    Route::resource('city', 'CityController');
    Route::resource('location', 'DeliveryLocationController');
});




