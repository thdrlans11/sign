<?php

use Illuminate\Support\Facades\Route;

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

// 로그인
Route::middleware('guest')->prefix('member')->controller(\App\Http\Controllers\Member\LoginController::class)->group(function() {
    Route::get('login', 'loginShow')->name('loginShow');
    Route::post('login', 'loginProcess')->name('loginProcess');
});

//공지사항
Route::prefix('board/{code}')->middleware('boardCheck')->controller(\App\Http\Controllers\Board\BoardController::class)->group(function() {
    Route::get('', 'list')->name('board.list');
    Route::get('calendar', 'calendar')->name('board.calendar');
    Route::get('form/{sid?}', 'form')->name('board.form');     
    Route::post('upsert/{sid?}', 'upsert')->name('board.upsert');
    Route::get('view/{sid}', 'view')->name('board.view');
    Route::get('delete/{sid}', 'delete')->name('board.delete');
    Route::post('dbChange', 'dbChange')->name('board.dbChange');
    Route::post('preview', 'popupPreview')->name('board.popupPreview');
});

require __DIR__.'/common.php';