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
    return redirect('login');
});

Auth::routes();
Route::post('/register/loadteam', [App\Http\Controllers\Auth\RegisterController::class, 'loadTeam'])->name('register.loadTeam');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//REPORTS
Route::get('/reports/multifilters', [App\Http\Controllers\ReportsController::class, 'index_multifilters'])->middleware('role:Administrator')->name('report.multifilters');
Route::get('/reports/multifilters/generate', [App\Http\Controllers\ReportsController::class, 'multifiltersGenerate'])->name('report.multifiltersGenerate');
Route::get('/reports/usertimesheet/{department?}/{team?}/{userid?}', [App\Http\Controllers\ReportsController::class, 'index_usertimesheets'])->name('report.usertimesheets');
Route::get('/reports/usertimesheets/generate', [App\Http\Controllers\ReportsController::class, 'timeSheetListByUser'])->name('report.timeSheetListByUser');

//DASHBOARD
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Scheduling Manager,Drafting Admin')->name('dashboard');

//USER
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->middleware('role:Administrator')->name('user');
Route::get('/users/list', [App\Http\Controllers\UserController::class, 'userList'])->name('user.list');
Route::post('/users/list/loadroles', [App\Http\Controllers\UserController::class, 'loadRoles'])->name('user.loadRoles');
Route::post('/users/list/updateroles', [App\Http\Controllers\UserController::class, 'updateRoles'])->name('user.updateRoles');
Route::get('/users/list/deleteuser/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('user.deleteUser');
Route::GET('/users/getdrafters', [App\Http\Controllers\UserController::class, 'getDrafters'])->name('user.getDrafters');
Route::GET('/users/getschedulers', [App\Http\Controllers\UserController::class, 'getSchedulers'])->name('user.getSchedulers');
Route::GET('/users/getusersbyteam', [App\Http\Controllers\UserController::class, 'getUsersByTeam'])->name('user.getUsersByTeam');
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

// Categories
Route::get('/prestarts', [App\Http\Controllers\PrestartController::class, 'index'])->middleware('role:Administrator')->name('prestart');
Route::get('/prestarts/list', [App\Http\Controllers\PrestartController::class, 'preStartList'])->name('prestart.list');
Route::post('/prestarts/insert', [App\Http\Controllers\PrestartController::class, 'insert'])->name('prestart.insert');
Route::get('/prestarts/list/deleteprestart/{id}', [App\Http\Controllers\PrestartController::class, 'deletePrestart'])->name('prestart.deleteprestart');
Route::GET('/prestarts/getprestarts', [App\Http\Controllers\PrestartController::class, 'getPrestarts'])->name('prestart.getPrestarts');

//Timesheets
Route::get('/draftingmaster/timesheets/id/{id}', [App\Http\Controllers\TimesheetsController::class, 'index_drafting'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Checker,Drafter')->name('timesheets.drafting');
Route::get('/draftingmaster/timesheets/fetch/{id}', [App\Http\Controllers\TimesheetsController::class, 'timeSheetListDrafting'])->name('timesheets.fetchDrafting');
Route::get('/schedulingmaster/timesheets/id/{id}', [App\Http\Controllers\TimesheetsController::class, 'index_scheduling'])->middleware('role:Administrator,Scheduling Manager,Scheduling Admin,Senior Scheduler')->name('timesheets.scheduling');
Route::get('/schedulingmaster/timesheets/fetch/{id}', [App\Http\Controllers\TimesheetsController::class, 'timeSheetListScheduling'])->name('timesheets.fetchScheduling');

