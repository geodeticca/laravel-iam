<?php

Route::namespace('App\Http\Controllers\Iam')->middleware(['web', 'auth'])->group(function () {

    Route::match(['get', 'post'], 'account', ['as' => 'account', 'uses' => 'AccountController@account']);
});
