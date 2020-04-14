<?php

Route::namespace('App\Http\Controllers\Iam')->middleware(['web'])->group(function () {

    // account
    Route::match(['get', 'post'], 'account', ['as' => 'account', 'uses' => 'AccountController@account']);
});
