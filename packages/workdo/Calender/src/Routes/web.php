<?php

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

use Illuminate\Support\Facades\Route;
use Workdo\Calender\Http\Controllers\CalenderController;

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::group(['middleware' => 'PlanModuleCheck:Calender'], function () {
        Route::prefix('calender')->group(function () {
            Route::get('/calendars', [CalenderController::class, 'index'])->name('calender.index');
            Route::post('/google-calendar', [CalenderController::class, 'saveGoogleCalenderSettings'])->name('google.calender.settings');
        });
    });
});
