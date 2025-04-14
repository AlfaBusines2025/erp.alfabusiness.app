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
use Workdo\ToDo\Http\Controllers\TaskStageController;
use Workdo\ToDo\Http\Controllers\ToDoController;

Route::middleware(['web', 'auth', 'verified', 'PlanModuleCheck:ToDo'])->group(function () {
    Route::prefix('todo')->group(function () {

        Route::resource('to-do', ToDoController::class);
        Route::get('/board', [ToDoController::class,'board'])->name('to-do.board');
        Route::post('board/order-update',[ToDoController::class,'taskOrderUpdate'])->name('todo.update.order');
        Route::get('calendar',[ToDoController::class,'calendar'])->name('to-do.calendar');
        Route::get('/to-do/{id}/description', [ToDoController::class,'description'])->name('to-do.description');
        Route::get('to-do/status/{id}', [ToDoController::class, 'status'])->name('to-do.status');
        Route::PUT('to-do/status-update/{id}', [ToDoController::class, 'statusUpdate'])->name('to-do.status.update');

        Route::resource('stage', TaskStageController::class);

    });
});
