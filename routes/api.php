<?php

Route::get('test', '\App\Http\Actions\TestAction');

Route::post('login', '\App\Http\Actions\LoginAction');
Route::post('register', '\App\Http\Actions\RegisterAction');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', '\App\Http\Actions\LogoutAction');
});

Route::group(['middleware' => ['auth.jwt', 'check-email']], function () {
    Route::get('tasks', 'TaskController@index');
    Route::get('tasks/{id}', 'TaskController@show');
    Route::post('tasks', 'TaskController@store');
    Route::put('tasks/{id}', 'TaskController@update');
    Route::delete('tasks/{id}', 'TaskController@destroy');
});
