<?php

use Illuminate\Support\Facades\Route;

Route::get('/schedulingmaster', [App\Http\Controllers\SchedulingMasterController::class, 'index'])
->middleware('role:Administrator,Scheduling Manager,Scheduling Admin,Senior Scheduler')->name('scheduling_master');
Route::get('/schedulingmaster/list', [App\Http\Controllers\SchedulingMasterController::class, 'schedulingMasterList'])->name('scheduling_master.list');
Route::post('/schedulingmaster/insert', [App\Http\Controllers\SchedulingMasterController::class, 'insert'])->name('scheduling_master.insert');
Route::get('/schedulingmaster/fetch/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetch'])->name('scheduling_master.fetch');
Route::get('/schedulingmaster/fetch/schedulers/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetchSchedulers'])->name('scheduling_master.fetch_schedulers');
Route::post('/schedulingmaster/edit', [App\Http\Controllers\SchedulingMasterController::class, 'edit'])->name('scheduling_master.edit');
Route::post('/schedulingmaster/assignschedulers', [App\Http\Controllers\SchedulingMasterController::class, 'assignschedulers'])->name('scheduling_master.assign_schedulers');
Route::post('/schedulingmaster/editschedulers', [App\Http\Controllers\SchedulingMasterController::class, 'editschedulers'])->name('scheduling_master.edit_schedulers');
Route::post('/schedulingmaster/assignchecker', [App\Http\Controllers\SchedulingMasterController::class, 'assignChecker'])->name('scheduling_master.assign_checker');
Route::post('/schedulingmaster/editchecker', [App\Http\Controllers\SchedulingMasterController::class, 'editChecker'])->name('scheduling_master.edit_checker');
Route::get('/schedulingmaster/fetch/checker/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetchChecker'])->name('scheduling_master.fetch_checker');
Route::get('/schedulingmaster/submitjob/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'submitJob'])->name('scheduling_master.submit_job');
Route::get('/schedulingmaster/canceljob/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'cancelJob'])->name('scheduling_master.cancel_job');
Route::get('/schedulingmaster/list/{status}', [App\Http\Controllers\SchedulingMasterController::class, 'fetchByStatusList'])->name('scheduling_master_fetch_by_status_list.list');

