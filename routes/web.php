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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::post('/register/loadteam', [App\Http\Controllers\Auth\RegisterController::class, 'loadTeam'])->name('register.loadTeam');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//DASHBOARD
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Drafting Admin')->name('dashboard');
Route::post('/dashboard/getactiveusers', [App\Http\Controllers\DashboardController::class, 'getActiveUsers'])->name('dashboard.getActiveUsers');
Route::post('/dashboard/getinactiveusers', [App\Http\Controllers\DashboardController::class, 'getInactiveUsers'])->name('dashboard.getInactiveUsers');

Route::post('/dashboard/getfeeds', [App\Http\Controllers\DashboardController::class, 'getFeeds'])->name('dashboard.getFeeds');
Route::post('/dashboard/getaveragedraftinghours', [App\Http\Controllers\DashboardController::class, 'getAverageDraftingHours'])->name('dashboard.getAverageDraftingHours');

//USER
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->middleware('role:Administrator')->name('user');
Route::get('/users/list', [App\Http\Controllers\UserController::class, 'userList'])->name('user.list');
Route::post('/users/list/loadroles', [App\Http\Controllers\UserController::class, 'loadRoles'])->name('user.loadRoles');
Route::post('/users/list/updateroles', [App\Http\Controllers\UserController::class, 'updateRoles'])->name('user.updateRoles');
Route::get('/users/list/deleteuser/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('user.deleteUser');
Route::GET('/users/getdrafters', [App\Http\Controllers\UserController::class, 'getDrafters'])->name('user.getDrafters');
Route::GET('/users/getcheckers', [App\Http\Controllers\UserController::class, 'getCheckers'])->name('user.getCheckers');
Route::GET('/users/readactivities', [App\Http\Controllers\UserController::class, 'readActivities'])->name('user.readActivities');
Route::GET('/users/getactivities', [App\Http\Controllers\UserController::class, 'getActivities'])->name('user.getActivities');
Route::GET('/users/countactivities', [App\Http\Controllers\UserController::class, 'countActivities'])->name('user.countActivities');

//Customers
Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->middleware('role:Administrator')->name('customer');
Route::post('/customers/insert', [App\Http\Controllers\CustomerController::class, 'insert'])->name('customer.insert');
Route::get('/customers/list', [App\Http\Controllers\CustomerController::class, 'customerList'])->name('customer.list');
Route::get('/customers/list/deletecustomer/{id}', [App\Http\Controllers\CustomerController::class, 'deleteCustomer'])->name('customer.deleteCustomer');
Route::GET('/customers/getcustomers', [App\Http\Controllers\CustomerController::class, 'getCustomers'])->name('customer.getCustomers');

//Brands
Route::get('/brands', [App\Http\Controllers\BrandController::class, 'index'])->middleware('role:Administrator')->name('brand');
Route::get('/brands/list', [App\Http\Controllers\BrandController::class, 'brandList'])->name('brand.list');
Route::post('/brands/insert', [App\Http\Controllers\BrandController::class, 'insert'])->name('brand.insert');
Route::get('/brands/list/deletebrand/{id}', [App\Http\Controllers\BrandController::class, 'deleteBrand'])->name('brand.deleteBrand');
Route::GET('/brands/getbrands', [App\Http\Controllers\BrandController::class, 'getBrands'])->name('brand.getBrands');

// Types
Route::get('/types', [App\Http\Controllers\TypeController::class, 'index'])->middleware('role:Administrator')->name('type');
Route::get('/types/list', [App\Http\Controllers\TypeController::class, 'typeList'])->name('type.list');
Route::post('/types/insert', [App\Http\Controllers\TypeController::class, 'insert'])->name('type.insert');
Route::get('/types/list/deletetype/{id}', [App\Http\Controllers\TypeController::class, 'deleteType'])->name('type.deleteType');
Route::GET('/types/gettypes', [App\Http\Controllers\TypeController::class, 'getTypes'])->name('type.getTypes');

// Job Types
Route::get('/jobtypes', [App\Http\Controllers\JobTypeController::class, 'index'])->middleware('role:Administrator')->name('job_type');
Route::get('/jobtypes/list', [App\Http\Controllers\JobTypeController::class, 'jobTypeList'])->name('job_type.list');
Route::post('/jobtypes/insert', [App\Http\Controllers\JobTypeController::class, 'insert'])->name('job_type.insert');
Route::get('/jobtypes/list/deletejobtype/{id}', [App\Http\Controllers\JobTypeController::class, 'deleteJobType'])->name('job_type.deleteJobType');
Route::GET('/jobtypes/getjobtypes', [App\Http\Controllers\JobTypeController::class, 'getJobTypes'])->name('job_type.getJobTypes');

// Categories
Route::get('/categories', [App\Http\Controllers\CategoriesController::class, 'index'])->middleware('role:Administrator')->name('categories');
Route::get('/categories/list', [App\Http\Controllers\CategoriesController::class, 'categoriesList'])->name('categories.list');
Route::post('/categories/insert', [App\Http\Controllers\CategoriesController::class, 'insert'])->name('categories.insert');
Route::get('/categories/list/deletecategories/{id}', [App\Http\Controllers\CategoriesController::class, 'deleteCategories'])->name('categories.deleteCategories');
Route::GET('/categories/getcategories', [App\Http\Controllers\CategoriesController::class, 'getCategories'])->name('categories.getCategories');

