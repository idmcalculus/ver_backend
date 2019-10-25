<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// User
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('verify_user/{data}', 'UserController@verifyUser');
Route::post('reset_password_request', 'UserController@resetPasswordRequest');
Route::post('reset_password', 'UserController@resetPassword');

// Category
Route::post('category/list', 'CategoryController@list');

// Career
Route::post('career/list', 'CareerController@list');
Route::post('career/show', 'CareerController@show');

// Career Application
Route::post('career_application/create', 'CareerApplicationController@create');
Route::post('career_application/show', 'CareerApplicationController@show');

// Investment
Route::post('investment/list', 'InvestmentController@list');
Route::post('investment/listByCategory', 'InvestmentController@listByCategory');
Route::post('investment/list_by_category_name', 'InvestmentController@listByCategoryName');
Route::post('investment/show', 'InvestmentController@show');

// Bank
Route::post('bank/list', 'BankController@list');

Route::group(['middleware' => 'auth:api'], function() {

    // User
    Route::post('update_user', 'UserController@updateUser');
    Route::post('activate_user', 'UserController@activateUser');
    Route::post('deactivate_user', 'UserController@deactivateUser');
    Route::post('change_password', 'UserController@changePassword');
    Route::post('update_preference', 'UserController@updatePreference');
    Route::post('update_account_detail', 'UserController@updateAccountDetail');
    Route::post('admin/update_user', 'UserController@adminUpdateUser');
    Route::post('fetch_profile', 'UserController@fetchProfile');
    Route::post('user/list', 'UserController@list');
    Route::post('user/validate_otp', 'UserController@validateOTP');

    // Category
    Route::post('category/create', 'CategoryController@create');
    Route::post('category/update', 'CategoryController@update');
    Route::post('category/delete', 'CategoryController@delete');

    // Career
    Route::post('career/create', 'CareerController@create');
    Route::post('career/update', 'CareerController@update');
    Route::post('career/delete', 'CareerController@delete');

    // Career Application
    Route::post('career_application/list', 'CareerApplicationController@list');
    Route::post('career_application/list_career_applications', 'CareerApplicationController@list_career_applications');
    Route::post('career_application/delete', 'CareerApplicationController@delete');

    // Investment
    Route::post('investment/create', 'InvestmentController@create');
    Route::post('investment/update', 'InvestmentController@update');
    Route::post('investment/endInvestment', 'InvestmentController@endInvestment');
    Route::post('investment/startInvestment', 'InvestmentController@startInvestment');
    Route::post('investment/investment_payout_user', 'InvestmentController@investmentPayoutUsers');

    // Investment User
    Route::post('investment_user/listInvestmentUser', 'UserInvestmentController@listInvestmentUser');
    Route::post('investment_user/create', 'UserInvestmentController@create');
    Route::post('investment_user/listInvestmentOfUser', 'UserInvestmentController@listInvestmentOfUser');
    Route::post('investment_user/pullOutOfInvestment', 'UserInvestmentController@pullOutOfInvestment');

    // Investment Report
    Route::post('report/create', 'ReportController@create');
    Route::post('report/list', 'ReportController@list');
    Route::post('report/update', 'ReportController@update');
    Route::post('report/delete', 'ReportController@delete');

    Route::post('report/adminDashboard', 'ReportController@adminDashboard');
    Route::post('report/userDashboard', 'ReportController@userDashboard');

    // Investment Request
    Route::post('request/create', 'InvestmentRequestController@create');
    Route::post('request/approve', 'InvestmentRequestController@approve');
    Route::post('request/delete', 'InvestmentRequestController@delete');
    Route::post('request/listAll', 'InvestmentRequestController@listAll');
    Route::post('request/listByInvestment', 'InvestmentRequestController@listByInvestment');

    // Message Request
    Route::post('message/send', 'MessageController@send');
    Route::post('message/list_admin', 'MessageController@list_admin');
    Route::post('message/list_users', 'MessageController@list_users');
    Route::post('message/list_all_messages', 'MessageController@list_all_messages');
    Route::post('message/fetch_last_message', 'MessageController@fetch_last_message');
    Route::post('message/read_message', 'MessageController@read_message');
});
