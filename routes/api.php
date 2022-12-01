<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AdminController::class, 'login']);

// Protected Route
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Ticket Path
    Route::get('user/tickets',[TicketController::class,'index']);
    Route::post('user/ticket/{id}',[TicketController::class,'show']);
    Route::post('user/edit/ticket/{id}',[TicketController::class,'edit']);
    Route::post('user/tickets/checked/{id}',[TicketController::class,'checked']);
    Route::post('user/tickets/approved/{id}',[TicketController::class,'approved']);

    // LogOut
    Route::get('/logout', [AdminController::class, 'logout']);

    // Admin
    Route::get('user', [AdminController::class, 'index']);

    // Log Path
    Route::get('user/log', [LogController::class, 'index']);
    Route::post('user/clear/log', [LogController::class, 'destory']);
});
