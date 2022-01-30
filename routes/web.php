<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web', 'admin.guest'])->group(static function () {
    Route::namespace('App\Http\Controllers\Admin\Auth')->group(static function () {
        Route::get('/admin/login', 'LoginController@showLoginForm')->name('zekini/livewire-crud-generator::admin/login');
        Route::post('/admin/login', 'LoginController@login');

        Route::get('/admin/password-reset', 'ForgotPasswordController@showLinkRequestForm')->name('zekini/livewire-crud-generator::admin/password/showForgotForm');
        Route::post('/admin/password-reset/send', 'ForgotPasswordController@sendResetLinkEmail');
        Route::get('/admin/password-reset/{token}', 'ResetPasswordController@showResetForm')->name('zekini/livewire-crud-generator::admin/password/showResetForm');
        Route::post('/admin/password-reset/reset', 'ResetPasswordController@reset');
    });
});

Route::middleware(['web', 'admin:'.config('zekini-admin.defaults.guard')])->group(function(){
    Route::namespace('App\Http\Controllers\Admin\Auth')->group(static function () {
        Route::any('/admin/logout', 'LoginController@logout')->name('zekini/livewire-crud-generator::admin/logout');
    });
});

Route::middleware(['web', 'admin:'.config('zekini-admin.defaults.guard')])->group(static function () {
    Route::namespace('App\Http\Controllers\Admin')->group(static function () {
        Route::get('/admin', 'AdminHomepageController@index')->name('zekini/livewire-crud-generator::admin');
    });
});
