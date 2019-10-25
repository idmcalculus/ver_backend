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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcomemail', function () {
    return view('mail.welcomemail');
});

Route::get('/resetpassword', function () {
    return view('mail.resetpassword');
});

Route::get('/payment_confirmation', function () {
    return view('mail.receipt');
});
