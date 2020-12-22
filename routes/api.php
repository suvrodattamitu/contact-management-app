<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([

    'prefix'    => 'user',
    'namespace' => 'User'
    
],function(){
    
    //passed
    Route::post('register','AuthController@register');

    //passed
    Route::post('login','AuthController@login');

    //passed
    Route::post('contact/add','ContactController@store');

    //passed
    Route::get('contacts/{token}','ContactController@allcontacts');

    //passed
    Route::get('contact/edit/{id}/{token}','ContactController@edit');

    //passed
    Route::put('contact/update/{id}','ContactController@update');

    //passed
    Route::delete('contact/delete/{id}','ContactController@destroy');

});
