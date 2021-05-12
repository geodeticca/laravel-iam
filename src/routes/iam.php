<?php

//use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Auth;

Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false,
]);

Route::namespace('App\Http\Controllers\Iam')->middleware(['web', 'auth'])->group(function () {

    // account
    Route::match(['get', 'post'], 'account', ['as' => 'account', 'uses' => 'AccountController@account']);
});
