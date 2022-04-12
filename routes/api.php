<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatbotController;

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

Route::group(['prefix' => 'conversation', 'as' => 'api.conversation.'], function () {
    Route::post('message', [ChatbotController::class, 'sendMessage'])->name('message');
    Route::get('characters', [ChatbotController::class, 'getCharacters'])->name('characters');
    Route::get('films', [ChatbotController::class, 'getFilms'])->name('films');
});
