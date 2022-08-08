<?php

use Illuminate\Support\Facades\Route;

Route::get('/schedulingmaster', [App\Http\Controllers\SchedulingMasterController::class, 'index'])
->middleware('role:Administrator,Scheduling Manager,Scheduling Admin,Senior Scheduler')->name('scheduling_master');
Route::get('/schedulingmaster/list', [App\Http\Controllers\SchedulingMasterController::class, 'schedulingMasterList'])->name('scheduling_master.list');
Route::post('/schedulingmaster/insert', [App\Http\Controllers\SchedulingMasterController::class, 'insert'])->name('scheduling_master.insert');
Route::get('/schedulingmaster/fetch/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetch'])->name('scheduling_master.fetch');
Route::get('/schedulingmaster/fetch/schedulers/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetchScheduler'])->name('scheduling_master.fetchScheduler');
Route::post('/schedulingmaster/edit', [App\Http\Controllers\SchedulingMasterController::class, 'edit'])->name('scheduling_master.edit');
Route::post('/schedulingmaster/assignscheduler', [App\Http\Controllers\SchedulingMasterController::class, 'assignScheduler'])->name('scheduling_master.assignScheduler');
Route::post('/schedulingmaster/editschedulers', [App\Http\Controllers\SchedulingMasterController::class, 'editScheduler'])->name('scheduling_master.editScheduler');
Route::post('/schedulingmaster/assignchecker', [App\Http\Controllers\SchedulingMasterController::class, 'assignChecker'])->name('scheduling_master.assignChecker');
Route::get('/schedulingmaster/fetch/checker/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetchChecker'])->name('scheduling_master.fetchChecker');
Route::post('/schedulingmaster/editchecker', [App\Http\Controllers\SchedulingMasterController::class, 'editChecker'])->name('scheduling_master.editChecker');

Route::get('/schedulingmaster/submitjob/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'submitJob'])->name('scheduling_master.submit_job');
Route::get('/schedulingmaster/canceljob/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'cancelJob'])->name('scheduling_master.cancel_job');
Route::get('/schedulingmaster/list/{status}', [App\Http\Controllers\SchedulingMasterController::class, 'fetchByStatusList'])->name('scheduling_master_fetch_by_status_list.list');


//My Schedules
Route::get('/schedulingmaster/myschedules', [App\Http\Controllers\MySchedulesController::class, 'index'])->middleware('role:Administrator,Scheduling Manager,Scheduling TL,Scheduling Checker,Scheduler,Senior Scheduler')->name('my_schedules');
Route::get('/schedulingmaster/myschedules/list', [App\Http\Controllers\MySchedulesController::class, 'mySchedulesList'])->name('my_schedules.list');
Route::get('/schedulingmaster/myschedules/list/setstatus/{id}', [App\Http\Controllers\MySchedulesController::class, 'setStatusOnOff'])->name('my_schedules.setStatusOnOff');
Route::get('/schedulingmaster/myschedules/list/setjobstatus/{id}', [App\Http\Controllers\MySchedulesController::class, 'setJobStatus'])->name('my_schedules.setJobStatus');

//My Schedules Check
Route::get('/schedulingmaster/myschedulescheck', [App\Http\Controllers\MySchedulesCheckController::class, 'index'])->middleware('role:Administrator,Scheduling Manager,Scheduling TL,Scheduling Checker,Senior Scheduler')->name('my_schedules_check');
// Route::get('/schedulingmaster/myschedulescheck/list', [App\Http\Controllers\myschedulesCheckController::class, 'myschedulesCheckList'])->name('my_drafts_check.list');
// Route::get('/schedulingmaster/myschedulescheck/list/setstatus/{id}', [App\Http\Controllers\myschedulesCheckController::class, 'setStatusOnOff'])->name('my_drafts_check.setStatusOnOff');
// Route::get('/schedulingmaster/myschedulescheck/list/setjobstatus/{id}', [App\Http\Controllers\myschedulesCheckController::class, 'setJobStatus'])->name('my_drafts_check.setJobStatus');
// Route::get('/schedulingmaster/myschedulescheck/list/reject/{id}', [App\Http\Controllers\myschedulesCheckController::class, 'rejectCheck'])->name('my_drafts_check.rejectCheck');

