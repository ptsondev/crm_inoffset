<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AjaxController;

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
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role == 1 || $user->role == 2) {
            return redirect('/project');
        } else {
            return redirect('/task/my-tasks');
        }
    }
    return redirect('/login');
});

Auth::routes();

Route::group(['middleware' => 'passvarstoview'], function () {
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('project', ProjectController::class)->middleware('auth');
    Route::get('task/my-tasks', [App\Http\Controllers\ProjectController::class, 'my_tasks'])->middleware('auth')->name('my-tasks');
    Route::get('thuchi', [App\Http\Controllers\ProjectController::class, 'thuchi'])->middleware('auth')->name('thuchi');



    Route::post('ajax/show-process', [AjaxController::class, 'show_process'])->middleware('auth')->name('ajax.show-process');
    Route::post('ajax/reload-notification', [AjaxController::class, 'reload_notification'])->middleware('auth')->name('ajax.reload-notification');
    Route::post('ajax/read-notification', [AjaxController::class, 'read_notification'])->middleware('auth')->name('ajax.read-notification');
    Route::post('ajax/task-finish', [AjaxController::class, 'task_finish'])->middleware('auth')->name('ajax.task-finish');
    Route::post('ajax/task-reply', [AjaxController::class, 'task_reply'])->middleware('auth')->name('ajax.task-reply');
    Route::post('ajax/add-thuchi-to-project', [AjaxController::class, 'add_thuchi_to_project'])->middleware('auth')->name('ajax.add-thuchi-to-project');
});
