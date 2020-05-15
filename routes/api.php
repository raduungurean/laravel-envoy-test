<?php

Route::get('test', '\App\Http\Actions\TestAction');

Route::post('login', '\App\Http\Actions\LoginAction');
Route::post('register', '\App\Http\Actions\RegisterAction');
Route::get('email/verify/{id}/{hash}', '\App\Http\Actions\EmailVerifyAction');
Route::post('password-recovery-email', '\App\Http\Actions\PasswordRecoveryLinkAction');
Route::get('password/{id}/{hash}', '\App\Http\Actions\RecoverPasswordFormAction');
Route::post('password', '\App\Http\Actions\RecoverPasswordAction');

Route::post('validate-access-token', '\App\Http\Actions\ValidateAccessTokenAction');
Route::post('create-account', '\App\Http\Actions\CreateAccountAction');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', '\App\Http\Actions\LogoutAction');
});

Route::group(['middleware' => ['auth.jwt', 'check-email']], function () {
    Route::get('players', '\App\Http\Actions\PlayersAction');
    Route::get('player-picture', '\App\Http\Actions\PlayerPictureAction');
    Route::get('players/{groupId}', '\App\Http\Actions\PlayersAction');
});

Route::group(['middleware' => ['auth.jwt']], function () {
    Route::put('profile', '\App\Http\Actions\ProfileUpdateAction');
    Route::put('change-password', '\App\Http\Actions\ChangePasswordAction');
    Route::post('profile-picture', '\App\Http\Actions\ProfilePictureAction');
});
