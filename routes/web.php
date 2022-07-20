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

//USER
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->middleware('role:Administrator')->name('user');
Route::get('/users/list', [App\Http\Controllers\UserController::class, 'userList'])->name('user.list');
Route::post('/users/list/loadroles', [App\Http\Controllers\UserController::class, 'loadRoles'])->name('user.loadRoles');
Route::post('/users/list/updateroles', [App\Http\Controllers\UserController::class, 'updateRoles'])->name('user.updateRoles');
Route::get('/users/list/deleteuser/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('user.deleteUser');
Route::GET('/users/getuser', [App\Http\Controllers\UserController::class, 'getUser'])->name('user.getUser');
Route::GET('/users/getcheckers', [App\Http\Controllers\UserController::class, 'getCheckers'])->name('user.getCheckers');

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
Route::GET('/jobtypes/getjobtypes', [App\Http\Controllers\JobTypeController::class, 'getJobTypes'])->name('customer.getJobTypes');

// Categories
Route::get('/categories', [App\Http\Controllers\CategoriesController::class, 'index'])->middleware('role:Administrator')->name('categories');
Route::get('/categories/list', [App\Http\Controllers\CategoriesController::class, 'categoriesList'])->name('categories.list');
Route::post('/categories/insert', [App\Http\Controllers\CategoriesController::class, 'insert'])->name('categories.insert');
Route::get('/categories/list/deletecategories/{id}', [App\Http\Controllers\CategoriesController::class, 'deleteCategories'])->name('categories.deleteCategories');
Route::GET('/categories/getcategories', [App\Http\Controllers\CategoriesController::class, 'getCategories'])->name('categories.getCategories');

//Drafting Master
Route::get('/draftingmaster', [App\Http\Controllers\DraftingMasterController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Admin')->name('drafting_master');
Route::get('/draftingmaster/list', [App\Http\Controllers\DraftingMasterController::class, 'draftingMasterList'])->name('drafting_master.list');
Route::post('/draftingmaster/insert', [App\Http\Controllers\DraftingMasterController::class, 'insert'])->name('drafting_master.insert');
Route::get('/draftingmaster/fetch/{id}', [App\Http\Controllers\DraftingMasterController::class, 'fetch'])->name('drafting_master.fetch');
Route::get('/draftingmaster/fetch/drafters/{id}', [App\Http\Controllers\DraftingMasterController::class, 'fetchDrafters'])->name('drafting_master.fetch_drafters');
Route::post('/draftingmaster/edit', [App\Http\Controllers\DraftingMasterController::class, 'edit'])->name('drafting_master.edit');
Route::post('/draftingmaster/assigndrafters', [App\Http\Controllers\DraftingMasterController::class, 'assignDrafters'])->name('drafting_master.assign_drafters');
Route::post('/draftingmaster/editdrafters', [App\Http\Controllers\DraftingMasterController::class, 'editDrafters'])->name('drafting_master.edit_drafters');
Route::post('/draftingmaster/assignchecker', [App\Http\Controllers\DraftingMasterController::class, 'assignChecker'])->name('drafting_master.assign_checker');
Route::post('/draftingmaster/editchecker', [App\Http\Controllers\DraftingMasterController::class, 'editChecker'])->name('drafting_master.edit_checker');
Route::get('/draftingmaster/fetch/checker/{id}', [App\Http\Controllers\DraftingMasterController::class, 'fetchChecker'])->name('drafting_master.fetch_checker');
Route::get('/draftingmaster/submitjob/{id}', [App\Http\Controllers\DraftingMasterController::class, 'submitJob'])->name('drafting_master.submit_job');
Route::get('/draftingmaster/canceljob/{id}', [App\Http\Controllers\DraftingMasterController::class, 'cancelJob'])->name('drafting_master.cancel_job');
Route::get('/draftingmaster/list/{status}', [App\Http\Controllers\DraftingMasterController::class, 'fetchByStatusList'])->name('drafting_master_fetch_by_status_list.list');

//Drafting Master Submitted
Route::get('/draftingmaster/submitted', [App\Http\Controllers\DraftingMasterController::class, 'index_submitted'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Manager')->name('drafting_master.submitted_jobs');

//Drafting Master Cancelled
Route::get('/draftingmaster/cancelled', [App\Http\Controllers\DraftingMasterController::class, 'index_cancelled'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Manager')->name('drafting_master.cancelled_jobs');


//My Drafts
Route::get('/draftingmaster/mydrafts', [App\Http\Controllers\MyDraftsController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Checker,Drafter')->name('my_drafts');
Route::get('/draftingmaster/mydrafts/list', [App\Http\Controllers\MyDraftsController::class, 'myDraftsList'])->name('my_drafts.list');
Route::get('/draftingmaster/mydrafts/list/setstatus/{id}', [App\Http\Controllers\MyDraftsController::class, 'setStatusOnOff'])->name('my_drafts.setStatusOnOff');
Route::get('/draftingmaster/mydrafts/list/setjobstatus/{id}', [App\Http\Controllers\MyDraftsController::class, 'setJobStatus'])->name('my_drafts.setJobStatus');


//My Drafts Check
Route::get('/draftingmaster/mydraftscheck', [App\Http\Controllers\MyDraftsCheckController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Checker')->name('my_drafts_check');
Route::get('/draftingmaster/mydraftscheck/list', [App\Http\Controllers\MyDraftsCheckController::class, 'myDraftsCheckList'])->name('my_drafts_check.list');
Route::get('/draftingmaster/mydraftscheck/list/setstatus/{id}', [App\Http\Controllers\MyDraftsCheckController::class, 'setStatusOnOff'])->name('my_drafts_check.setStatusOnOff');
Route::get('/draftingmaster/mydraftscheck/list/setjobstatus/{id}/{sixstars}', [App\Http\Controllers\MyDraftsCheckController::class, 'setJobStatus'])->name('my_drafts_check.setJobStatus');
Route::get('/draftingmaster/mydraftscheck/list/reject/{id}', [App\Http\Controllers\MyDraftsCheckController::class, 'rejectCheck'])->name('my_drafts_check.rejectCheck');


//Timesheets
Route::get('/draftingmaster/timesheets/id/{id}', [App\Http\Controllers\TimesheetsController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Drafting TL,Drafting Checker,Drafter')->name('timesheets');
Route::get('/draftingmaster/timesheets/fetch/{id}', [App\Http\Controllers\TimesheetsController::class, 'timeSheetList'])->name('timesheets.fetch');

//Shifting Schedule
Route::get('/shiftingschedule', [App\Http\Controllers\ShiftingScheduleController::class, 'index'])->middleware('role:Administrator')->name('shifting_schedule');
Route::post('/shiftingschedule/update', [App\Http\Controllers\ShiftingScheduleController::class, 'updateSchedule'])->name('shifting_schedule.update');

//SixStars
Route::get('/draftingmaster/sixstars', [App\Http\Controllers\SixStarsController::class, 'index'])->middleware('role:Administrator,Drafting Manager,Six Stars,Drafting Admin')->name('sixstars');
Route::get('/draftingmaster/sixstars/getforsixstars', [App\Http\Controllers\SixStarsController::class, 'getForSixStars'])->name('sixstars.get_for_six_stars');
Route::get('/draftingmaster/sixstars/addsixstars/{id}', [App\Http\Controllers\SixStarsController::class, 'addSixStars'])->name('sixstars.add_six_stars');
Route::get('/draftingmaster/sixstars/list', [App\Http\Controllers\SixStarsController::class, 'sixStarsList'])->name('sixstars.list');
Route::get('/draftingmaster/sixstars/list/ammend/{id}', [App\Http\Controllers\SixStarsController::class, 'ammendJob'])->name('sixstars.ammend_job');
Route::get('/draftingmaster/sixstars/list/submit/{id}', [App\Http\Controllers\SixStarsController::class, 'submitJob'])->name('sixstars.submit_job');