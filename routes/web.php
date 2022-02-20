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
    });
});


Route::middleware(['web', 'auth', 'role:' . admin_roles()])->group(static function () {
    Route::namespace('App\Http\Controllers\Admin')->group(static function () {
        Route::get('/admin', 'AdminHomepageController@index')->name('zekini/livewire-crud-generator::admin');
    });
});
