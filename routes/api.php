<?php

use Illuminate\Http\Request;
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

// 로그인
Route::prefix('auth')->controller(\App\Http\Controllers\Api\AuthController::class)->group(function() {    
    Route::post('/', 'auth')->name('api.auth');
    Route::post('/logout', 'logout')->middleware('authTokenCheck')->name('api.logout');
});

Route::middleware('authTokenCheck')->prefix('sign')->controller(\App\Http\Controllers\Api\SignController::class)->group(function() {
    Route::post('/', 'list')->name('api.signList');
    Route::post('/file', 'file')->name('api.signFile');
    Route::post('/fileDel', 'fileDel')->name('api.signFileDel');
    Route::post('/view', 'signView')->name('api.signView');
});