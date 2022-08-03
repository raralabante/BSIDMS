<?php

use Illuminate\Support\Facades\Route;

Route::get('/schedulingmaster', [App\Http\Controllers\SchedulingMasterController::class, 'index'])
->middleware('role:Administrator,Scheduling Manager,Scheduling Admin,Senior Scheduler')->name('scheduling_master');
Route::get('/schedulingmaster/list', [App\Http\Controllers\SchedulingMasterController::class, 'schedulingMasterList'])->name('scheduling_master.list');
Route::post('/schedulingmaster/insert', [App\Http\Controllers\SchedulingMasterController::class, 'insert'])->name('scheduling_master.insert');
Route::get('/schedulingmaster/fetch/{id}', [App\Http\Controllers\SchedulingMasterController::class, 'fetch'])->name('scheduling_master.fetch');