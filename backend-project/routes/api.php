<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ContactController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    //Rutas del controlador ChatController
    Route::get('/chats', [ChatController::class, 'getChats']);
    Route::get('/chats/{chatId}/messages', [ChatController::class, 'chatMessages']);
    Route::post('/chats/messages', [ChatController::class, 'sendMessage']);
    Route::get('/contacts', [ChatController::class, 'showContacts']);
    Route::post('/messages/resend', [ChatController::class, 'messageResend']);
    Route::post('/messages/statusUpdate', [ChatController::class, 'updateStatusMessage']);
});
