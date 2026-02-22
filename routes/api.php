<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(TaskController::class)->prefix('tasks')->group(function () {
	Route::get('/', 'index');
	Route::post('/', 'store');
	Route::put('/{task}', 'update');
	Route::patch('/{task}/items/{item}/status', 'updateItemStatus');
	Route::delete('/{task}', 'destroy');
});
