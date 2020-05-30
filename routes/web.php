<?php

use Illuminate\Support\Facades\Route;

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

Route::get('qr/{code}', 'ItemController@show')->name('item.show');

Route::middleware('guest')->group(function () {
    Route::view('login', 'auth.login')->name('login');
});

Route::view('password/reset', 'auth.passwords.email')->name('password.request');
Route::get('password/reset/{token}', 'Auth\PasswordResetController')->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::view('/', 'welcome')->name('home');

    Route::post('logout', 'Auth\LogoutController')->name('logout');

    Route::get('bar/orders', 'OrderController@index')->name('order.index');
    Route::get('bar/history', 'OrderHistoryController@index')->name('order.history');
});
