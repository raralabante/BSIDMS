<?php

use Illuminate\Support\Facades\Route;

Route::get('/schedulingmaster', [App\Http\Controllers\SchedulingMasterController::class, 'index'])
->middleware('role:Administrator,Scheduling Manager,Scheduling Admin,Senior Scheduler')->name('scheduling_master');