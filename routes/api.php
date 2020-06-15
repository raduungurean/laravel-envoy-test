<?php

Route::get('test', '\App\Http\Actions\TestAction');

Route::post('login', '\App\Http\Actions\LoginAction');
Route::post('register', '\App\Http\Actions\RegisterAction');
Route::get('email/verify/{id}/{hash}', '\App\Http\Actions\EmailVerifyAction');
Route::get('accept-invite/{code}', '\App\Http\Actions\InviteAcceptFormAction');
Route::post('accept-invite', '\App\Http\Actions\InviteAcceptAction');
Route::post('password-recovery-email', '\App\Http\Actions\PasswordRecoveryLinkAction');
Route::get('password/{id}/{hash}', '\App\Http\Actions\RecoverPasswordFormAction');
Route::post('password', '\App\Http\Actions\RecoverPasswordAction');

Route::post('validate-access-token', '\App\Http\Actions\ValidateAccessTokenAction');
Route::post('create-account', '\App\Http\Actions\CreateAccountFromProviderAction');

Route::post('log', '\App\Http\Actions\LogAction');
Route::get('logs', '\App\Http\Actions\LogsAction');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', '\App\Http\Actions\LogoutAction');
});

Route::group(['middleware' => ['auth.jwt', 'check-email']], function () {
    Route::get('players', '\App\Http\Actions\PlayersAction');
    Route::get('players/{groupId}', '\App\Http\Actions\PlayersAction');
    Route::post('groups', '\App\Http\Actions\AddGroupAction');
    Route::delete('groups/{groupId}', '\App\Http\Actions\DeleteGroupAction');
    Route::get('groups/{groupId}', '\App\Http\Actions\GroupAction');
    Route::put('groups/{groupId}', '\App\Http\Actions\EditGroupAction');
    Route::post('invite-player', '\App\Http\Actions\InvitePlayerAction');
});

Route::group(['middleware' => ['auth.jwt']], function () {
    Route::get('player-picture', '\App\Http\Actions\PlayerPictureAction');
    Route::put('profile', '\App\Http\Actions\ProfileUpdateAction');
    Route::get('verify-user-exists', '\App\Http\Actions\VerifyUserExistsAction');
    Route::put('change-password', '\App\Http\Actions\ChangePasswordAction');
    Route::post('profile-picture', '\App\Http\Actions\ProfilePictureAction');
});
