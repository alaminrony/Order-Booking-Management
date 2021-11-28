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

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return view('dashboard');
//})->name('dashboard');

Route::get('/', function () {
    return redirect('login');
});

Route::get('login', 'LoginController@index')->name('login');
Route::post('login', 'LoginController@login')->name('login');
Route::post('logout', 'LoginController@logout')->name('logout');
Route::get('forget-password', 'LoginController@forgetPass')->name('forgetPass');
Route::post('recovery-password', 'LoginController@recoveryPassword')->name('recoveryPassword');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');


    //User
    Route::get('user', 'UserController@index')->name('user.index');
    Route::post('user/view', 'UserController@view')->name('user.view');
    Route::get('user-filter', 'UserController@filter')->name('user.filter');
    Route::get('user/{id}/transaction', 'UserController@transaction')->name('user.transaction');
    Route::post('user/create', 'UserController@create')->name('user.create');
    Route::post('user/store', 'UserController@store')->name('user.store');
    Route::post('user/edit', 'UserController@edit')->name('user.edit');
    Route::post('user/update', 'UserController@update')->name('user.update');
    Route::delete('user/{id}/delete', 'UserController@destroy')->name('user.destroy');

    //User role
    Route::get('user-role', 'UserRoleController@index')->name('role.index');
    Route::get('user-role-filter', 'UserRoleController@filter')->name('role.filter');
    Route::post('user-role/create', 'UserRoleController@create')->name('role.create');
    Route::post('user-role/store', 'UserRoleController@store')->name('role.store');
    Route::post('user-role/edit', 'UserRoleController@edit')->name('role.edit');
    Route::post('user-role/update', 'UserRoleController@update')->name('role.update');
    Route::delete('user-role/{id}/delete', 'UserRoleController@destroy')->name('role.destroy');


    //Buyer Management
    Route::get('buyer-list', 'BuyerController@index')->name('buyer.index');
    Route::get('buyer-filter', 'BuyerController@filter')->name('buyer.filter');
    Route::post('buyer-list/create', 'BuyerController@create')->name('buyer.create');
    Route::post('buyer-list/store', 'BuyerController@store')->name('buyer.store');
    Route::post('buyer-list/edit', 'BuyerController@edit')->name('buyer.edit');
    Route::post('buyer-list/update', 'BuyerController@update')->name('buyer.update');
    Route::delete('buyer-list/{id}/delete', 'BuyerController@destroy')->name('buyer.destroy');

    //department Management
    Route::get('buyerVsUsers-list', 'BuyerVsUsersController@index')->name('buyerVsUsers.index');
    Route::get('buyerVsUsers-filter', 'BuyerVsUsersController@filter')->name('buyerVsUsers.filter');
    Route::post('buyerVsUsers/create', 'BuyerVsUsersController@create')->name('buyerVsUsers.create');
    Route::post('buyerVsUsers/store', 'BuyerVsUsersController@store')->name('buyerVsUsers.store');
    Route::post('buyerVsUsers/edit', 'BuyerVsUsersController@edit')->name('buyerVsUsers.edit');
    Route::post('buyerVsUsers/update', 'BuyerVsUsersController@update')->name('buyerVsUsers.update');
    Route::delete('buyerVsUsers/{id}/delete', 'BuyerVsUsersController@destroy')->name('buyerVsUsers.destroy');


    //department Management
    Route::get('department-list', 'DepartmentController@index')->name('department.index');
    Route::get('department-filter', 'DepartmentController@filter')->name('department.filter');
    Route::post('department/create', 'DepartmentController@create')->name('department.create');
    Route::post('department/store', 'DepartmentController@store')->name('department.store');
    Route::post('department/edit', 'DepartmentController@edit')->name('department.edit');
    Route::post('department/update', 'DepartmentController@update')->name('department.update');
    Route::delete('department/{id}/delete', 'DepartmentController@destroy')->name('department.destroy');

    //department vs user Management
    Route::get('departmentVsUsers-list', 'DepartmentVsUsersController@index')->name('departmentVsUsers.index');
    Route::get('departmentVsUsers-filter', 'DepartmentVsUsersController@filter')->name('departmentVsUsers.filter');
    Route::post('departmentVsUsers/create', 'DepartmentVsUsersController@create')->name('departmentVsUsers.create');
    Route::post('departmentVsUsers/store', 'DepartmentVsUsersController@store')->name('departmentVsUsers.store');
    Route::post('departmentVsUsers/edit', 'DepartmentVsUsersController@edit')->name('departmentVsUsers.edit');
    Route::post('departmentVsUsers/update', 'DepartmentVsUsersController@update')->name('departmentVsUsers.update');
    Route::delete('departmentVsUsers/{id}/delete', 'DepartmentVsUsersController@destroy')->name('departmentVsUsers.destroy');


    //department vs user Management
    Route::get('orderBooking-list', 'OrderBookingController@index')->name('orderBooking.index');
    Route::get('orderBooking-filter', 'OrderBookingController@filter')->name('orderBooking.filter');
    Route::post('orderBooking/create', 'OrderBookingController@create')->name('orderBooking.create');
    Route::post('orderBooking/store', 'OrderBookingController@store')->name('orderBooking.store');
    Route::post('orderBooking/edit', 'OrderBookingController@edit')->name('orderBooking.edit');
    Route::post('orderBooking/update', 'OrderBookingController@update')->name('orderBooking.update');
    Route::delete('orderBooking/{id}/delete', 'OrderBookingController@destroy')->name('orderBooking.destroy');



    //Settings
    Route::get('setting', 'SettingController@index')->name('setting.index');
    Route::get('setting-filter', 'SettingController@filter')->name('setting.filter');
    Route::post('setting/create', 'SettingController@create')->name('setting.create');
    Route::post('setting/store', 'SettingController@store')->name('setting.store');
    Route::post('setting/edit', 'SettingController@edit')->name('setting.edit');
    Route::post('setting/update', 'SettingController@update')->name('setting.update');
    Route::delete('setting/{id}/delete', 'SettingController@destroy')->name('setting.destroy');

    //Bank Ledger Module
    Route::get('transaction-list', 'TransactionController@index')->name('transaction.index');
    Route::get('transaction-filter', 'TransactionController@filter')->name('transaction.filter');
    Route::post('voucher-entry/create', 'TransactionController@create')->name('transaction.create');
    Route::post('voucher-entry/getIssue', 'TransactionController@getIssue')->name('transaction.getIssue');
    Route::post('voucher-entry/store', 'TransactionController@store')->name('transaction.store');
    Route::get('voucher/{id}/view', 'TransactionController@view')->name('transaction.view');
    Route::post('voucher/edit', 'TransactionController@edit')->name('transaction.edit');
    Route::post('voucher/update', 'TransactionController@update')->name('transaction.update');
    Route::delete('voucher/{id}/delete', 'TransactionController@destroy')->name('transaction.destroy');
});
