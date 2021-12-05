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

Route::middleware(['web'])->group(static function () {
    Route::namespace('Zekini\CrudGenerator\Http\Controllers\Auth')->group(static function () {
        Route::get('/admin/login', 'LoginController@showLoginForm')->name('zekini/livewire-crud-generator::admin/login');
        Route::post('/admin/login', 'LoginController@login');

        Route::any('/admin/logout', 'LoginController@logout')->name('zekini/livewire-crud-generator::admin/logout');

        Route::get('/admin/password-reset', 'ForgotPasswordController@showLinkRequestForm')->name('zekini/livewire-crud-generator::admin/password/showForgotForm');
        Route::post('/admin/password-reset/send', 'ForgotPasswordController@sendResetLinkEmail');
        Route::get('/admin/password-reset/{token}', 'ResetPasswordController@showResetForm')->name('zekini/livewire-crud-generator::admin/password/showResetForm');
        Route::post('/admin/password-reset/reset', 'ResetPasswordController@reset');

        Route::get('/admin/activation/{token}', 'ActivationController@activate')->name('zekini/livewire-crud-generator::admin/activation/activate');
    });
});

Route::middleware(['web', 'auth:' . config('zekini-admin.defaults.guard')])->group(static function () {
    Route::namespace('Zekini\CrudGenerator\Http\Controllers')->group(static function () {
        Route::get('/admin', 'AdminHomepageController@index')->name('zekini/livewire-crud-generator::admin');
    });
});
