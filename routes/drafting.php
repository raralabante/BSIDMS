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
Route::get('/draftingmaster/mydraftscheck/list/setjobstatus/{id}', [App\Http\Controllers\MyDraftsCheckController::class, 'setJobStatus'])->name('my_drafts_check.setJobStatus');
Route::get('/draftingmaster/mydraftscheck/list/reject/{id}', [App\Http\Controllers\MyDraftsCheckController::class, 'rejectCheck'])->name('my_drafts_check.rejectCheck');





//Shifting Schedule
Route::get('/shiftingschedule', [App\Http\Controllers\ShiftingScheduleController::class, 'index'])->middleware('role:Administrator')->name('shifting_schedule');
Route::post('/shiftingschedule/update', [App\Http\Controllers\ShiftingScheduleController::class, 'updateSchedule'])->name('shifting_schedule.update');

//SixStars
Route::get('/draftingmaster/sixstars', [App\Http\Controllers\SixStarsController::class, 'index'])->middleware('role:Administrator,Six Stars')->name('sixstars');
Route::get('/draftingmaster/sixstars/getforsixstars', [App\Http\Controllers\SixStarsController::class, 'getForSixStars'])->name('sixstars.get_for_six_stars');
Route::get('/draftingmaster/sixstars/addsixstars/{id}', [App\Http\Controllers\SixStarsController::class, 'addSixStars'])->name('sixstars.add_six_stars');
Route::get('/draftingmaster/sixstars/list', [App\Http\Controllers\SixStarsController::class, 'sixStarsList'])->name('sixstars.list');
Route::get('/draftingmaster/sixstars/list/ammend/{id}', [App\Http\Controllers\SixStarsController::class, 'ammendJob'])->name('sixstars.ammend_job');
Route::get('/draftingmaster/sixstars/list/submit/{id}', [App\Http\Controllers\SixStarsController::class, 'submitJob'])->name('sixstars.submit_job');